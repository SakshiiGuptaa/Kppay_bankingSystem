<?php

namespace App\Http\Controllers\Frontend;

use Validator;
use App\Models\User;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Facades\Txn\Txn;
use App\Models\Currency;
use App\Models\OthersBank;
use App\Enums\TransferType;
use App\Models\Beneficiary;
use App\Models\Transaction;
use App\Traits\NotifyTrait;
use App\Models\WireTransfar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FundTransferController extends Controller
{
    use NotifyTrait;

    public function index($code = 'default')
    {
        if (! setting('transfer_status', 'permission') || ! Auth::user()->transfer_status) {
            notify()->error(__('Fund transfer currently unavailble!'), 'Error');

            return to_route('user.dashboard');
        } elseif (! setting('kyc_fund_transfer') && ! auth()->user()->kyc) {
            notify()->error(__('Please verify your KYC.'), 'Error');

            return to_route('user.dashboard');
        }

        $banks = OthersBank::active()->get();
        session(['wallet_type' => $code]);
        //get wallets of specified user
        $wallets = auth()->user()->wallets->load('currency');

        return view('frontend::fund_transfer.index', compact('banks', 'code','wallets'));
    }

    public function getBeneficiary($bankId)
    {
        if ($bankId != '0') {
            $beneficiaries = Beneficiary::own()->where('bank_id', $bankId)->get();
            $banksData = OthersBank::find($bankId);
        } else {
            $beneficiaries = Beneficiary::own()->where('bank_id', '=', null)->get();
            $banksData = [
                'minimum_transfer' => setting('min_fund_transfer','fee'),
                'maximum_transfer' => setting('max_fund_transfer','fee'),
                'charge_type' => 'percentage',
                'charge' => setting('fund_transfer_charge','fee')
            ];
        }

        return response()->json([
            'beneficiaries' => $beneficiaries,
            'banksData' => $banksData,
        ]);
    }

    public function transfer(Request $request)
    {
        if (! setting('transfer_status', 'permission') || ! Auth::user()->transfer_status) {
            notify()->error(__('Fund transfer currently unavailble!'), 'Error');

            return to_route('user.dashboard');
        } elseif (! setting('kyc_fund_transfer') && ! auth()->user()->kyc) {
            notify()->error(__('Please verify your KYC.'), 'Error');

            return to_route('user.dashboard');
        }

        $validator = Validator::make($request->all(), [
            'bank_id' => 'required',
            'beneficiary_id' => 'nullable',
            'from' => 'required|string',
            'wallet_type' => 'required|string',
            'manual_data.account_name' => 'required_if:beneficiary_id,""',
            'manual_data.account_number' => 'required_if:beneficiary_id,""',
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        ], [
            'manual_data.*.required_if' => __('Select beneficiary or Fill up account name,number,branch name.'),
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
        $bankInfo = OthersBank::find($input['bank_id']);
        $amount = $input['amount'];
        // currency
        $currency = setting('currency', 'global');
        $currencySymbol = setting('currency_symbol', 'global');

        // Data of transfer
        $beneficiaryDetails = Beneficiary::find($input['beneficiary_id']);
        $manual_data = $request->manual_data;

        $account_number = $beneficiaryDetails != null ? sanitizeAccountNumber($beneficiaryDetails->account_number) : sanitizeAccountNumber(data_get($manual_data, 'account_number'));
        $receiver = User::where('account_number', $account_number)->first();
        $own_bank_min_transfer = setting('min_fund_transfer','fee');
        $own_bank_max_transfer = setting('max_fund_transfer','fee');
        
        // Concatenate 'from' and 'wallet_type' values with a separator
        $transferFrom = $request->input('from') . '-' . $request->input('wallet_type');

        // Check receiver account is exists
        if ($request->bank_id == '0' && ! $receiver) {
            notify()->error(__('Receiver Account not found!'), 'Error');

            return back();
        }

        if ($request->bank_id != 0 && ($amount < $bankInfo->minimum_transfer || $amount > $bankInfo->maximum_transfer)) {
            $message = 'Please Transfer the Amount within the range '.$currencySymbol.$bankInfo->minimum_transfer.' to '.$currencySymbol.$bankInfo->maximum_transfer;
            notify()->error($message, 'Error');

            return redirect()->back();
        }

        if ($request->bank_id == 0 && ($amount < $own_bank_min_transfer || $amount > $own_bank_max_transfer)) {
            $message = 'Please Transfer the Amount within the range '.$currencySymbol.$own_bank_min_transfer.' to '.$currencySymbol.$own_bank_max_transfer;
            notify()->error($message, 'Error');

            return redirect()->back();
        }

        if ($bankInfo) {
            $charge = $bankInfo->charge_type == 'percentage' ? (($bankInfo->charge / 100) * $amount) : $bankInfo->charge;
        } else {
            $charge = setting('fund_transfer_charge_type','fee') == 'percentage' ? ( (setting('fund_transfer_charge','fee') / 100) * $amount) : setting('fund_transfer_charge','fee');
        }

        $finalAmount = (float) $amount + (float) $charge;
        $payAmount = $finalAmount;

        // Check user balance
        $walletType = session('wallet_type','default');

        if ($walletType != 'default') {
            $wallet_currency = Currency::with('userWallet')->where('code', $walletType)->first();

            $wallet = $wallet_currency?->userWallet;
            $balance = $wallet?->balance;
            $walletType = $wallet?->id;
        }else {
            $balance = auth()->user()->balance;
        }

        if ($balance < $finalAmount) {
            $message = __('Insufficient Balance. Your balance must be upper than '.$amount.$currency);
            notify()->error($message, 'Error');

            return redirect()->back();
        }

        $type = TxnType::FundTransfer;
        $transferType = $request->bank_id == 0 ? TransferType::OwnBankTransfer : TransferType::OtherBankTransfer;

        $txnInfo = Txn::transfer($amount, $charge, $finalAmount, 'Transfer to ' . $account_number, $type, TxnStatus::Pending, $currency, $payAmount, auth()->id(), null, 'User', $beneficiaryDetails->id ?? null, $input['purpose'], $transferType, $manual_data ?? [], $walletType, '',$transferFrom);

        $responseData = [
            'amount' => $amount,
            'currency' => $currencySymbol,
            'account' => $account_number,
            'tnx' => $txnInfo['tnx'],
        ];

        if ($responseData) {
            if ($request->bank_id == '0') {
                $transaction = Transaction::tnx($responseData['tnx']);

                $transaction->update([
                    'status' => TxnStatus::Success->value,
                ]);

                $txnInfo = (new Txn)->new($input['amount'], $charge, $finalAmount,'System', 'Received money from '.auth()->user()->full_name, $type,TxnStatus::Success, $currency, $payAmount, $receiver->id, null, 'User', [], approvalCause: $input['purpose']);

                // Increase receiver balance
                if ($walletType != 'default') {
                    $wallet?->increment('balance', $finalAmount);
                }else {
                    $receiver->increment('balance', $amount);
                }
            }

            // Decrease sender balance
            if ($walletType != 'default') {
                $wallet?->decrement('balance', $finalAmount);
            }else {
                auth()->user()->decrement('balance', $finalAmount);
            }
        }

        $user = auth()->user();

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[charge]]' => $txnInfo->charge,
            '[[amount]]' => $txnInfo->amount,
            '[[total_amount]]' => $txnInfo->final_amount,
            '[[account_number]]' => $account_number,
            '[[account_name]]' => data_get($manual_data, 'account_name'),
            '[[branch_name]]' => data_get($manual_data, 'branch_name'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->pushNotify('fund_transfer_request', $shortcodes, route('admin.fund.transfer.pending'), auth()->id(), 'Admin');
        $this->mailNotify($txnInfo->user->email, 'fund_transfer_request', $shortcodes);
        $this->smsNotify('fund_transfer_request', $shortcodes, $txnInfo->user->phone);

        $message = __('Fund Transfer Successfully!');

        return view('frontend::fund_transfer.success', compact('responseData', 'message'));
    }

    public function log()
    {
        $from_date = trim(@explode('-', request('daterange'))[0]);
        $to_date = trim(@explode('-', request('daterange'))[1]);

        $transactions = Transaction::fundTransfar()->where('user_id', auth()->id())
            ->search(request('trx'))
            ->when(request('daterange'), function ($query) use ($from_date, $to_date) {
                $query->whereDate('created_at', '>=', Carbon::parse($from_date)->format('Y-m-d'));
                $query->whereDate('created_at', '<=', Carbon::parse($to_date)->format('Y-m-d'));
            })
            ->latest()
            ->paginate(request('limit', 15));

        return view('frontend::fund_transfer.log', compact('transactions'));
    }

    public function wire()
    {
        if (! setting('transfer_status', 'permission') || ! Auth::user()->transfer_status) {
            notify()->error(__('Fund transfer currently unavailble!'), 'Error');

            return to_route('user.dashboard');
        } elseif (! setting('kyc_fund_transfer') && ! auth()->user()->kyc) {
            notify()->error(__('Please verify your KYC.'), 'Error');

            return to_route('user.dashboard');
        }

        $data = WireTransfar::first();
        $currency = setting('site_currency', 'global');

        return view('frontend::fund_transfer.wire_transfer', compact('data', 'currency'));
    }

    public function wirePost(Request $request)
    {
        if (! setting('transfer_status', 'permission') || ! Auth::user()->transfer_status) {
            notify()->error(__('Fund transfer currently unavailble!'), 'Error');

            return to_route('user.dashboard');
        } elseif (! setting('kyc_fund_transfer') && ! auth()->user()->kyc) {
            notify()->error(__('Please verify your KYC.'), 'Error');

            return to_route('user.dashboard');
        }

        $validator = Validator::make($request->all(), [
            'name_of_account' => 'required',
            'account_number' => 'required',
            'swift_code' => 'required',
            'full_name' => 'required',
            'phone_number' => 'required',
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
        $amount = $input['amount'];
        $wireTransfer = WireTransfar::first();
        $currency = setting('currency', 'global');
        $currencySymbol = setting('currency_symbol', 'global');
        
        if (($amount < $wireTransfer->minimum_transfer || $amount > $wireTransfer->maximum_transfer)) {
            $message = 'Please Transfer the Amount within the range '.$currencySymbol.$wireTransfer->minimum_transfer.' to '.$currencySymbol.$wireTransfer->maximum_transfer;
            notify()->error($message, 'Error');

            return redirect()->back();
        }

        $charge = $wireTransfer->charge_type == 'percentage' ? (($wireTransfer->charge / 100) * $amount) : $wireTransfer->charge;
        $finalAmount = (float) $amount + (float) $charge;
        $payAmount = $finalAmount;
        $type = TxnType::FundTransfer;
        $transferType = TransferType::WireTransfer;
        $manualField = [
            'name_of_account' => $request->name_of_account,
            'account_number' => $request->account_number,
            'swift_code' => $request->swift_code,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
        ];
        $txnInfo = Txn::transfer($input['amount'], $charge, $finalAmount, 'Wire Transfer to '.$request->account_number, $type, TxnStatus::Pending, $currency, $payAmount, auth()->id(), null, 'User', null, $input['purpose'], $transferType, $manualField);
        $responseData = [
            'amount' => $amount,
            'currency' => $currencySymbol,
            'account' => $request->account_number,
            'tnx' => $txnInfo['tnx'],
        ];

        $user = auth()->user();
        $manual_data = json_decode($txnInfo->manual_field_data);

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[charge]]' => $txnInfo->charge,
            '[[amount]]' => $txnInfo->amount,
            '[[total_amount]]' => $txnInfo->final_amount,
            '[[total_amount]]' => $txnInfo->final_amount,
            '[[status]]' => $txnInfo->status->value,
            '[[account_number]]' => data_get($manual_data, 'account_number'),
            '[[name_of_account]]' => data_get($manual_data, 'name_of_account'),
            '[[swift_code]]' => data_get($manual_data, 'swift_code'),
            '[[phone_number]]' => data_get($manual_data, 'phone_number'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify($txnInfo->user->email, 'wire_transfer', $shortcodes);
        $this->smsNotify('wire_transfer', $shortcodes, $txnInfo->user->phone);
        $this->pushNotify('wire_transfer_request', $shortcodes, route('admin.fund.transfer.pending'), $txnInfo->user->id, 'Admin');

        $message = __('Wire Transfer Successfully!');

        return view('frontend::fund_transfer.success', compact('responseData', 'message'));
    }
}
