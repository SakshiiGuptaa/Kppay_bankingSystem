<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WithdrawAccount;
use App\Models\WithdrawalSchedule;
use App\Models\WithdrawMethod;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Traits\Payment;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DepositMethod;
use Session;
use Txn;
use Validator;
use App\Services\WithdrawalService;

class WithdrawController extends Controller
{
    use ImageUpload, NotifyTrait, Payment;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $accounts = WithdrawAccount::with('method')->where('user_id', auth()->id())->get();

        return view('frontend::withdraw.account.index', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        if (! setting('kyc_withdraw') && ! auth()->user()->kyc) {
            notify()->error(__('Please verify your KYC.'), 'Error');

            return to_route('user.dashboard');
        }

        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (isset($value['value']) && is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value']);
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        WithdrawAccount::create($data);

        notify()->success(__('Withdraw cccount created successfully'), 'success');

        return redirect()->route('user.withdraw.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $withdrawMethods = WithdrawMethod::where('status', true)->get();

        return view('frontend::withdraw.account.create', compact('withdrawMethods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $withdrawMethods = WithdrawMethod::all();
        $withdrawAccount = WithdrawAccount::find($id);

        return view('frontend::withdraw.account.edit', compact('withdrawMethods', 'withdrawAccount'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $withdrawAccount = WithdrawAccount::find($id);

        $oldCredentials = json_decode($withdrawAccount->credentials, true);

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (! isset($value['value'])) {
                $credentials[$key]['value'] = data_get($oldCredentials[$key], 'value');
            }

            if (isset($value['value']) && is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value'], data_get($oldCredentials[$key], 'value'));
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        $withdrawAccount->update($data);
        notify()->success(__('Withdraw account updated successfully'), 'success');

        return redirect()->route('user.withdraw.account.index');

    }

    public function delete($id)
    {
        WithdrawAccount::destroy(decrypt($id));

        notify()->success(__('Withdraw account deleted successfully!'), 'Success');

        return back();
    }

    /**
     * @return string
     */
    public function withdrawMethod($id)
    {
        $withdrawMethod = WithdrawMethod::find($id);

        if ($withdrawMethod) {
            return view('frontend::withdraw.include.__account', compact('withdrawMethod'))->render();
        }

        return '';
    }

    /**
     * @return array
     */
public function details($accountId, int $amount = 0)
{
    try {
        $withdrawAccount = WithdrawAccount::with('method')->find($accountId);

        if (!$withdrawAccount) {
            return response()->json(['error' => 'Withdraw account not found'], 404);
        }

        if (!$withdrawAccount->method) {
            return response()->json(['error' => 'Withdraw method not associated'], 404);
        }

        $method = $withdrawAccount->method;

        $charge = $method->charge ?? 0;
        $currency = $method->currency ?? 'XOF';  // Update this line

        $info = [
            'name' => $method->name ?? 'N/A',
            'charge' => $charge,
            'charge_type' => $method->charge_type ?? 'fixed',
            'range' => 'Minimum '.$method->min_withdraw.' '.$currency.' and Maximum '.$method->max_withdraw.' '.$currency,
            'rate' => $method->rate ?? 1,
            'pay_currency' => $method->currency ?? 'XOF',
            'logo' => "<img src='".asset($method->icon ?? 'default.png')."'>",
        ];

        return response()->json(['info' => $info]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server error: '.$e->getMessage()], 500);
    }
}


public function withdrawNow(Request $request)
{
    if (!setting('user_withdraw', 'permission') || !Auth::user()->withdraw_status) {
        return response()->json([
            'status' => 'failed',
            'message' => __('Withdraw currently unavailable!'),
        ]);
    }

    $withdrawOffDays = WithdrawalSchedule::where('status', 0)->pluck('name')->toArray();
    $today = Carbon::now()->format('l');

    if (in_array($today, $withdrawOffDays)) {
        return response()->json([
            'status' => 'failed',
            'message' => __('Today is the off day of withdraw'),
        ]);
    }

    $validator = Validator::make($request->all(), [
        'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        'withdraw_account' => 'required',
        'phone_number' => 'required|regex:/^[0-9]{10,15}$/', // Validate phone number
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'failed',
            'message' => $validator->errors()->first(),
        ]);
    }

    $input = $request->all();
    $amount = (float)$input['amount'];

    $withdrawAccount = WithdrawAccount::find($input['withdraw_account']);
    $withdrawMethod = $withdrawAccount->method;

    if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
        return response()->json([
            'status' => 'failed',
            'message' => __('Please withdraw the amount within the range :min to :max', [
                'min' => $withdrawMethod->min_withdraw,
                'max' => $withdrawMethod->max_withdraw,
            ]),
        ]);
    }

    $charge = $withdrawMethod->charge_type === 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;
    $totalAmount = $amount + (float)$charge;

    $user = Auth::user();
    if ($user->balance < $totalAmount) {
        return response()->json([
            'status' => 'failed',
            'message' => __('Insufficient balance'),
        ]);
    }

    $user->decrement('balance', $totalAmount);

    // WithdrawalService call
    $phoneNumber = $input['phone_number'];
    $transferResponse = WithdrawalService::transferFunds($amount, $phoneNumber);

    if (!$transferResponse['success']) {
        return response()->json([
            'status' => 'failed',
            'message' => $transferResponse['message'] ?? __('Transfer failed'),
            'amount' => $amount,
        ]);
    }

    // Check transfer status using checkTransferStatus
    $statusResponse = WithdrawalService::checkTransferStatus($transferResponse['referenceId'] ?? null);

    return response()->json([
        'status' => $statusResponse['status'] ?? 'success',
        'message' => __('Withdrawal successful. Status: :status', ['status' => $statusResponse['status'] ?? 'Unknown']),
        'transaction_id' => $transferResponse['referenceId'] ?? null,
    ]);
}


    /**
     * @return string
     */
    // public function withdrawNow(Request $request)
    // {

    //     if (! setting('user_withdraw', 'permission') || ! Auth::user()->withdraw_status) {
    //         notify()->error(__('Withdraw currently unavailble!'), 'Error');

    //         return back();
    //     }

    //     $withdrawOffDays = WithdrawalSchedule::where('status', 0)->pluck('name')->toArray();
    //     $date = Carbon::now();
    //     $today = $date->format('l');

    //     if (in_array($today, $withdrawOffDays)) {
    //         notify()->error(__('Today is the off day of withdraw'), 'Error');

    //         return back();
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
    //         'withdraw_account' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         notify()->error($validator->errors()->first(), 'Error');

    //         return redirect()->back();
    //     }

    //     //daily limit
    //     $todayTransaction = Transaction::whereIn('type', [TxnType::Withdraw, TxnType::WithdrawAuto])->whereDate('created_at', Carbon::today())->count();
    //     $dayLimit = (float) Setting('withdraw_day_limit', 'fee');
    //     if ($todayTransaction >= $dayLimit) {
    //         notify()->error(__('Today Withdraw limit has been reached'), 'Error');

    //         return redirect()->back();
    //     }

    //     $input = $request->all();
    //     $amount = (float) $input['amount'];

    //     $withdrawAccount = WithdrawAccount::find($input['withdraw_account']);
    //     $withdrawMethod = $withdrawAccount->method;

    //     if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
    //         $currencySymbol = setting('currency_symbol', 'global');
    //         $message = 'Please Withdraw the Amount within the range '.$currencySymbol.$withdrawMethod->min_withdraw.' to '.$currencySymbol.$withdrawMethod->max_withdraw;
    //         notify()->error($message, 'Error');

    //         return redirect()->back();
    //     }

    //     $charge = $withdrawMethod->charge_type == 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;
    //     $totalAmount = $amount + (float) $charge;

    //     $user = Auth::user();
    //     if ($user->balance < $totalAmount) {
    //         notify()->error(__('Insufficient Balance'), 'Error');

    //         return redirect()->back();
    //     }

    //     $user->decrement('balance', $totalAmount);

    //     $payAmount = $amount * $withdrawMethod->rate;

    //     $type = $withdrawMethod->type == 'auto' ? TxnType::WithdrawAuto : TxnType::Withdraw;

    //     $txnInfo = Txn::new(
    //         $input['amount'],
    //         $charge,
    //         $totalAmount,
    //         $withdrawMethod->name,
    //         'Withdraw With '.$withdrawAccount->method_name,
    //         $type,
    //         TxnStatus::Pending,
    //         $withdrawMethod->currency,
    //         $payAmount,
    //         $user->id,
    //         null,
    //         'User',
    //         json_decode($withdrawAccount->credentials, true)
    //     );

    //     if ($withdrawMethod->type == 'auto') {
    //         $gatewayCode = $withdrawMethod->gateway->gateway_code;

    //         return self::withdrawAutoGateway($gatewayCode, $txnInfo);

    //     }

    //     $symbol = setting('currency_symbol', 'global');
    //     $notify = [
    //         'card-header' => 'Withdraw Money',
    //         'title' => $symbol.$txnInfo->amount.' Withdraw Request Successful',
    //         'p' => 'The Withdraw Request has been successfully sent',
    //         'strong' => 'Transaction ID: '.$txnInfo->tnx,
    //         'action' => route('user.withdraw.view'),
    //         'a' => 'WITHDRAW REQUEST AGAIN',
    //         'view_name' => 'withdraw',
    //     ];
    //     Session::put('user_notify', $notify);
    //     $shortcodes = [
    //         '[[full_name]]' => $txnInfo->user->full_name,
    //         '[[txn]]' => $txnInfo->tnx,
    //         '[[method_name]]' => $withdrawMethod->name,
    //         '[[withdraw_amount]]' => $txnInfo->amount.setting('site_currency', 'global'),
    //         '[[site_title]]' => setting('site_title', 'global'),
    //         '[[site_url]]' => route('home'),
    //     ];

    //     $this->mailNotify(setting('site_email', 'global'), 'withdraw_request', $shortcodes);
    //     $this->pushNotify('withdraw_request', $shortcodes, route('admin.withdraw.pending'), $user->id);
    //     $this->smsNotify('withdraw_request', $shortcodes, $user->phone);

    //     return redirect()->route('user.notify');

    // }

    /**
     * @return Application|Factory|View
     */
    public function withdraw($code = 'default')
    {
        if (! setting('kyc_withdraw') && ! auth()->user()->kyc) {
            notify()->error(__('Please verify your KYC.'), 'Error');

            return to_route('user.dashboard');
        }

        $accounts = WithdrawAccount::with('method')->where('user_id', \Auth::id())->get();
        $accounts = $accounts->reject(function ($value, $key) {
            return ! $value->method->status;
        });
        $gateways = DepositMethod::where('status', 1)->get();
        $wallets = auth()->user()->wallets->load('currency');
        return view('frontend::withdraw.now', compact('code','accounts','gateways','wallets'));
    }

    public function withdrawLog()
    {
        $from_date = trim(@explode('-', request('daterange'))[0]);
        $to_date = trim(@explode('-', request('daterange'))[1]);

        $withdraws = Transaction::where('user_id', auth()->id())
            ->whereIn('type', [TxnType::Withdraw,TxnType::WithdrawAuto])
            ->search(request('trx'))
            ->when(request('daterange'), function ($query) use ($from_date, $to_date) {
                $query->whereDate('created_at', '>=', Carbon::parse($from_date)->format('Y-m-d'));
                $query->whereDate('created_at', '<=', Carbon::parse($to_date)->format('Y-m-d'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(request('limit', 15))
            ->withQueryString();

        return view('frontend::withdraw.log', compact('withdraws'));
    }
}
