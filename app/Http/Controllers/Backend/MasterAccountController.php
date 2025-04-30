<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterAccount;

class MasterAccountController extends Controller
{
    public function masterAccount(Request $request)
    {
        // Fetch all data from the master_account table
        $masterAccounts = MasterAccount::all();

        // Pass the data to the view
        return view('backend.transaction.master-account', compact('masterAccounts'));
    }
}

