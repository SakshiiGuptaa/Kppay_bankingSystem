<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function index()
    {
        abort_if(!setting('multiple_currency', 'permission'), 404);

        $user_wallets = auth()->user()->wallets->load('currency')->where('is_default', 0);
        $currencies = Currency::all();

        // Filter out the currencies that the user already has
        $currencies = $currencies->filter(function($currency) use ($user_wallets){
            return !in_array($currency->id, $user_wallets->pluck('currency_id')->toArray());
        });
        
         // Retrieve the default wallet (where is_default = 1)
        $default_wallet = auth()->user()->wallets()->where('is_default', 1)->with('currency')->first();

        return view('frontend::wallet.index', compact('user_wallets', 'currencies','default_wallet'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
           'currency' => 'required|exists:currencies,id',
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back()->with('error', __('Invalid currency'));
        }

        // add new wallet
        auth()->user()->wallets()->create([
            'currency_id' => $request->currency
        ]);

        notify()->success(__('New wallet added successfully'));
        return back();
    }
    
    public function default(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_id' => 'required|exists:user_wallets,id',
        ]);
    
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back()->with('error', __('Invalid Wallet'));
        }
    
        $walletId = $request->wallet_id;
    
        // Retrieve the wallet where id matches the walletId and ensure it belongs to the authenticated user
        $wallet = auth()->user()->wallets()->where('id', $walletId)->first();
    
        if (!$wallet) {
            notify()->error(__('Wallet not found or does not belong to you'), 'Error');
            return back();
        }
    
        // Update all wallets of the user to is_default = 0
        auth()->user()->wallets()->update(['is_default' => 0]);
    
        // Set the selected wallet to is_default = 1
        $updateSuccessful = $wallet->update(['is_default' => 1]);
    
        if (!$updateSuccessful) {
            notify()->error(__('Failed to update the default wallet'), 'Error');
            return back();
        }
    
        notify()->success(__('Default wallet updated successfully'));
        return back();
    }

    
    public function destroy(UserWallet $wallet){
        // Check if the wallet has balance
        if ($wallet->balance > 0) {
            notify()->error(__('You can not delete wallet with balance'), 'Error');
            return back();
        }

        // Delete the wallet
        $wallet->delete();

        notify()->success(__('Wallet deleted successfully'));
        return back();
    }
}
