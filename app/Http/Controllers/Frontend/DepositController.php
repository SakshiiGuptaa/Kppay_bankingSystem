<?php

namespace App\Http\Controllers\Frontend;

use Txn;
use Validator;
use Carbon\Carbon;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Models\Currency;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use App\Models\DepositMethod;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use App\Services\MomoPaymentHelper;

class DepositController extends GatewayController
{
    use ImageUpload, NotifyTrait;

    public function deposit($code = 'default')
    {
        if (! setting('user_deposit', 'permission') || ! Auth::user()->deposit_status) {
            notify()->error(__('Deposit currently unavailable'), 'Error');

            return to_route('user.dashboard');
        } elseif (! setting('kyc_deposit') && ! auth()->user()->kyc) {
            notify()->error(__('Please verify your KYC.'), 'Error');

            return to_route('user.dashboard');
        }

        $isStepOne = 'current';
        $isStepTwo = '';
        $gateways = DepositMethod::where('status', 1)->get();
        $wallets = auth()->user()->wallets->load('currency');
        return view('frontend::deposit.now', compact('isStepOne','code','isStepTwo', 'gateways','wallets'));
    }
    
    // Code for checking transaction status will shift after deposit now at last
    public function checkTransactionStatus(Request $request)
    {
        $validatedData = $request->validate([
            'transaction_id' => 'required|string',
            'userwallet_id' => 'required|integer',
        ]);
    
        $reference_id = $validatedData['transaction_id'];
        $userwallet_id = $validatedData['userwallet_id'];
        
        try {
            $response = MomoPaymentHelper::getTransactionStatus($reference_id);
        
            if (isset($response['status']) && $response['status'] === 'error') {
                // Handle error case
                return response()->json([
                    'status' => 'error',
                    'message' => $response['message'],
                ], 500);
            }
        
            $status = $response['status']; // Extract the transaction status
            $amount = $response['amount'] ?? 0; // Get the amount from the response if available
                
            // Find the transaction in the database
            $transaction = Transaction::where('reference_id', $reference_id)->first();
        
            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found',
                ], 404);
            }

        
            if ($status === 'SUCCESSFUL') {
        
                $transaction->status = TxnStatus::Success->value; // Assuming TxnStatus enum is used
                $transaction->save();
                
                // Update the user's wallet balance
                $wallet = UserWallet::find($userwallet_id);
                
                if ($wallet) {
                    $wallet->balance += $amount; // Add the transaction amount to the wallet balance
                    $wallet->save();
                }
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment successful',
                    'amount'=>$amount,
                    'updated_balance' => $wallet->balance,
                ]);
            } elseif ($status === 'PENDING') {
                // No database update for pending status, only return response
                return response()->json([
                    'status' => 'pending',
                    'message' => 'Transaction is still pending',
                ]);
            } elseif ($status === 'FAILED') {
                $transaction->status = TxnStatus::Failed->value; // Update status to failed
                $transaction->save();

                return response()->json([
                    'status' => 'failed',
                    'message' => 'Payment failed',
                    'amount' =>$amount,
                ]);
            } else {
                // Handle unknown status
                return response()->json([
                    'status' => 'unknown',
                    'message' => 'Unknown transaction status',
                    'response' => $response, // Include full response for debugging
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

    }


    public function depositNow(Request $request)
    {
        if (!setting('user_deposit', 'permission') || !Auth::user()->deposit_status) {
            return response()->json(['status' => 'error', 'message' => __('Deposit currently unavailable!')], 403);
        } elseif (!setting('kyc_deposit') && !auth()->user()->kyc) {
            return response()->json(['status' => 'error', 'message' => __('Please verify your KYC.')], 403);
        }
    
        // Validate incoming request data
        $validatedData = $request->validate([
            'wallet_type'   => 'required|numeric',
            'gateway_code'  => 'required|string',
            'phone_number'  => 'nullable|string', // Only required for certain gateways
            'amount'        => 'required|numeric|min:1',
        ]);
    
        try {
            $walletType   = $request->input('wallet_type');
            $gatewayCode  = $request->input('gateway_code');
            $msisdn       = $request->input('phone_number');
            $amount       = $request->input('amount');
    
            $input = $request->all();

        $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();
        $amount = $input['amount'];

        if ($amount < $gatewayInfo->minimum_deposit || $amount > $gatewayInfo->maximum_deposit) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Please Deposit the Amount within the range '.$currencySymbol.$gatewayInfo->minimum_deposit.' to '.$currencySymbol.$gatewayInfo->maximum_deposit;
            notify()->error($message, 'Error');

            return redirect()->back();
        } 

        $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
        $finalAmount = (float) $amount + (float) $charge;
        $payAmount = $finalAmount / $gatewayInfo->rate;
        $depositType = TxnType::Deposit;


        if (isset($input['manual_data'])) {
            $depositType = TxnType::ManualDeposit;
            $manualData = $input['manual_data'];

            foreach ($manualData as $key => $value) {

                if (is_file($value)) {
                    $manualData[$key] = self::imageUploadTrait($value);
                }
            }

        }

        // Wallet type
        $walletType = $request->get('wallet_type','default');
        
        //paymnet gateway logic
            $token    = MomoPaymentHelper::getAccessToken();
            $response = MomoPaymentHelper::initiatePayment($amount, $msisdn);
            $refId    = $response['uuid']; // Capture the reference ID
            $response = MomoPaymentHelper::getTransactionStatus($refId);
        
            if (isset($response['status']) && $response['status'] === 'error') {
                // Handle error case
                return response()->json([
                    'status' => 'error',
                    'message' => $response['message'],
                ], 500);
            }
        
            $status = $response['status']; // Extract the transaction status
        
            if ($status === 'SUCCESSFUL') {
                // Handle successful case
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment successful',
                ]);
            } elseif ($status === 'PENDING') {
                // Handle pending case
           $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code, 'Deposit With '.$gatewayInfo->name, $depositType, TxnStatus::Pending, $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User', $manualData ?? [], $walletType,$refId);
                return response()->json(['status' => 'pending', 'message' => 'Transaction is pending', 'transaction_id' => $refId,'userwallet_id'=>$walletType]);
            } elseif ($status === 'FAILED') {
                // Handle failed case
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Payment failed',
                    'amount' =>$amount,
                ]);
            } else {
                // Handle unknown status
                return response()->json([
                    'status' => 'unknown',
                    'message' => 'Unknown transaction status',
                    'response' => $response, // Include full response for debugging
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // public function depositNow(Request $request)
    // {
    //     if (! setting('user_deposit', 'permission') || ! Auth::user()->deposit_status) {
    //         notify()->error(__('Deposit currently unavailable!'), 'Error');

    //         return to_route('user.dashboard');
    //     } elseif (! setting('kyc_deposit') && ! auth()->user()->kyc) {
    //         notify()->error(__('Please verify your KYC.'), 'Error');

    //         return to_route('user.dashboard');
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'gateway_code' => 'required',
    //         'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
    //     ]);

    //     if ($validator->fails()) {
    //         notify()->error($validator->errors()->first(), 'Error');

    //         return redirect()->back();
    //     }

    //     $input = $request->all();

    //     $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();
    //     $amount = $input['amount'];

    //     if ($amount < $gatewayInfo->minimum_deposit || $amount > $gatewayInfo->maximum_deposit) {
    //         $currencySymbol = setting('currency_symbol', 'global');
    //         $message = 'Please Deposit the Amount within the range '.$currencySymbol.$gatewayInfo->minimum_deposit.' to '.$currencySymbol.$gatewayInfo->maximum_deposit;
    //         notify()->error($message, 'Error');

    //         return redirect()->back();
    //     } 

    //     $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
    //     $finalAmount = (float) $amount + (float) $charge;
    //     $payAmount = $finalAmount * $gatewayInfo->rate;
    //     $depositType = TxnType::Deposit;


    //     if (isset($input['manual_data'])) {
    //         $depositType = TxnType::ManualDeposit;
    //         $manualData = $input['manual_data'];

    //         foreach ($manualData as $key => $value) {

    //             if (is_file($value)) {
    //                 $manualData[$key] = self::imageUploadTrait($value);
    //             }
    //         }

    //     }

    //     // Wallet type
    //     $walletType = $request->get('wallet_type','default');
        
    //     $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code, 'Deposit With '.$gatewayInfo->name, $depositType, TxnStatus::Pending, $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User', $manualData ?? [], $walletType);

    //     return self::depositAutoGateway($gatewayInfo->gateway_code, $txnInfo);
    // }

    public function depositSuccess()
    {
        return view('frontend::deposit.success');
    }
    
        public function depositFailed()
    {
        return view('frontend::deposit.failed');
    }

    public function depositLog()
    {
        $from_date = trim(@explode('-', request('daterange'))[0]);
        $to_date = trim(@explode('-', request('daterange'))[1]);

        $deposits = Transaction::where('user_id', auth()->user()->id)
            ->search(request('trx'))
            ->whereIn('type', [TxnType::Deposit, TxnType::ManualDeposit])
            ->when(request('daterange'), function ($query) use ($from_date, $to_date) {
                $query->whereDate('created_at', '>=', Carbon::parse($from_date)->format('Y-m-d'));
                $query->whereDate('created_at', '<=', Carbon::parse($to_date)->format('Y-m-d'));
            })->latest()->paginate(request('limit', 15))->withQueryString();

        return view('frontend::deposit.log', compact('deposits'));
    }
}
