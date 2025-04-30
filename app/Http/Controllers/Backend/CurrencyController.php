<?php

namespace App\Http\Controllers\Backend;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    public function update(Request $request)
    {
        $all_currencies = $request->field_options;
    
        // Create or update the currencies from the list
        foreach ($all_currencies as $single_currency) {
            // Check if the currency exists and if it's not the default currency
            if ($this->checkCurrencyExists($single_currency) && $this->notExitsDefaultCurrency($single_currency)) {
                // Get the existing currency based on the code
                $currency = Currency::where('code', $single_currency['current_code'])->first();
    
                // Update the existing currency, including the new country_name field
                $currency->update([
                    'name' => $single_currency['name'],
                    'symbol' => $single_currency['symbol'],
                    'code' => $single_currency['code'],
                    'country_name' => $single_currency['country_name'], // Add country_name here
                ]);
            } 
            // If the currency does not exist and is not the default currency
            elseif ($this->checkCurrencyNotExists($single_currency) && $this->notExitsDefaultCurrency($single_currency)) {
                // Create a new currency, including the country_name field
                $currency = Currency::create([
                    'name' => $single_currency['name'],
                    'symbol' => $single_currency['symbol'],
                    'code' => $single_currency['code'],
                    'country_name' => $single_currency['country_name'], // Add country_name here
                ]);
            }
        }
    
        // Delete the currencies which are not in the list
        $delete_currency_ids = $request->delete_currencies;
        if ($delete_currency_ids) {
            Currency::doesnthave('userWallet')->whereIn('id', $delete_currency_ids)->delete();
        }
    
        // Success message and redirect
        notify()->success(__('Currency updated successfully!'), 'Success');
        return back();
    }

    public function destroy($id){
        // Find the currency and delete
        $currency = Currency::withCount('userWallet')->findOrFail($id);

        // Check if currency has wallet balance which is already in use
        if ($currency->user_wallets_count > 0) {
            return [
                'status' => 'error',
                'message' => 'This currency has wallet balance which is already in use!',
            ];
        }

        // Delete the currency
        $currency->delete();

        // Success message
        return [
            'status' => 'success',
            'message' => 'Currency deleted successfully!',
        ];
    }

    protected function checkCurrencyExists($single_currency){
        return isset($single_currency['current_name'], $single_currency['name'], $single_currency['current_symbol']) && ($single_currency['current_name'] != $single_currency['name'] || $single_currency['current_symbol'] != $single_currency['symbol'] || $single_currency['current_code'] != $single_currency['code']);
    }

    protected function checkCurrencyNotExists($single_currency){
        return !isset($single_currency['current_name'], $single_currency['name'], $single_currency['current_symbol']);
    }

    protected function notExitsDefaultCurrency($single_currency){
        return $single_currency['code'] != setting('site_currency', 'global');
    }
}
