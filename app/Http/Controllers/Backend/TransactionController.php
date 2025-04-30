<?php

namespace App\Http\Controllers\Backend;

use App\Models\Currency;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('permission:transaction-list');
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws \Exception
     */
    public function transactions(Request $request, $id = null)
    {
        $perPage = $request->perPage ?? 15;

        $status = $request->status ?? 'all';
        $search = $request->search ?? null;
        $type = $request->type ?? 'all';
        $transactions = Transaction::with('user')
                            ->search($search)
                            ->status($status)
                            ->type($type)
                            ->when(in_array(request('sort_field'),['created_at','final_amount','type','description']),function($query){
                                $query->orderBy(request('sort_field'),request('sort_dir'));
                            })
                            ->when(request('sort_field') == 'user',function($query){
                                $query->whereHas('user',function($userQuery){
                                    $userQuery->orderBy('username',request('sort_dir'));
                                });
                            })
                            ->when(!request()->has('sort_field'),function($query){
                                $query->latest();
                            })
                            ->paginate($perPage)
                            ->withQueryString();

        return view('backend.transaction.index', compact('transactions'));
    }

    public function wallets()
    {
        // $wallets = UserWallet::with('currency','user')
        //                     ->when(in_array(request('sort_field'),['created_at','balance']),function($query){
        //                         $query->orderBy(request('sort_field'),request('sort_dir'));
        //                     })
        //                     ->when(request('sort_field') == 'user',function($query){
        //                         $query->whereHas('user',function($userQuery){
        //                             $userQuery->orderBy('username',request('sort_dir'));
        //                         });
        //                     })
        //                     ->when(in_array(request('sort_field'),['currency_name','currency_symbol']),function($query){
        //                         $query->whereHas('currency',function($currencyQuery){
        //                             $currencyQuery->orderBy(request('sort_field'),request('sort_dir'));
        //                         });
        //                     })
        //                     ->latest()
        //                     ->paginate();
        
        // Fetch available currencies with sorting and filtering
    $currencies = Currency::query()
        ->when(in_array(request('sort_field'), ['name', 'symbol', 'code', 'country_name', 'created_at']), function ($query) {
            $query->orderBy(request('sort_field'), request('sort_dir', 'asc'));
        })
        ->latest() // Default sorting by the latest if no sorting field is specified
        ->paginate(); // Paginate the results

    // Return the view with the currencies data
        

        return view('backend.wallets.index',compact('currencies'));
    }

    public function virtualCardsList()
    {
        $cards = Card::with('user')
                        ->when(in_array(request('sort_field'),['created_at','balance','card_number','expiration_year']),function($query){
                            $query->orderBy(request('sort_field'),request('sort_dir'));
                        })
                        ->when(request('sort_field') == 'user',function($query){
                            $query->whereHas('user',function($userQuery){
                                $userQuery->orderBy('username',request('sort_dir'));
                            });
                        })
                        ->latest()
                        ->paginate();

        return view('backend.virtual_cards.index',compact('cards'));
    }
    
}
