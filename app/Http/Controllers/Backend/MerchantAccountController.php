<?php

namespace App\Http\Controllers\Backend;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MerchantAccountController extends Controller
{
    //merchant list page
    public function index()
    {
        // Fetch all products for the logged-in user
        $merchantAccounts = User::where('merchant_account_status', 1)->get();
    
        return view('backend.merchant_accounts.index', compact('merchantAccounts'));
    }
}
