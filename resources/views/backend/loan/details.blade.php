@extends('backend.layouts.app')
@section('title')
    {{ __('Loan Details') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Loan Details') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h4 class="title-small">{{ __('Plan Information') }}</h4>
                        </div>
                        <div class="site-card-body">
                            <form action="{{ route('admin.loan.updatestore', ['loan_id' => $loan->id]) }}" method="post" enctype="multipart/form-data" class="row">
                                @csrf
                                <div class="col-xl-12">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Plan Name:') }}</label>
                                        <input type="text" name="name" class="box-input" placeholder="Plan name" required value="{{ $loan->plan->name }}" readonly/>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Installment Rate:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="installment_rate" class="form-control" value="{{ $loan->installment_rate}}"/>
                                            <span class="input-group-text">{{ __('%') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Installment Interval:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="installment_intervel" class="form-control" value="{{ $loan->installment_interval }}"/>
                                            <span class="input-group-text">{{ __('Days') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Total Installment:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="total_installment" class="form-control" value="{{  $loan->total_installments }}"/>
                                            <span class="input-group-text">{{ __('Times') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Loan Amount:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="loan_amount" class="form-control" value="{{ $loan->amount}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Per Installment:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="per_installment" class="form-control" value="{{ ($loan->amount / 100) * $loan->installment_rate }}" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Given Installment:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="given_installment" class="form-control" value="{{ $loan->givenInstallemnt() ?? 0 }}" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Paid Amount:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="paid_amount" class="form-control" value="{{ $loan->transactions->sum('amount') ?? 0}}" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Payable Amount:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="payable_amount" class="form-control" value="{{ $loan->totalPayableAmount() }}" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Status:') }}</label>
                                        <div class="value">
                                        @include('backend.loan.include.__loan_status',['status' => $loan->status->value])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Bank Profit:') }}</label>
                                        <div class="input-group joint-input">
                                            <input type="text" name="bank_profit" class="form-control" value="{{ $loan->totalPayableAmount() - $loan->amount }}" readonly/>
                                        </div>
                                    </div>
                                </div>                                

                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn-sm primary-btn w-100">
                                        {{ __('Save Plan Changes') }}
                                    </button>
                                </div>
                            </form>
                          
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="site-card">
                        <div class="site-card-header">
                          <h4 class="title-small">{{ __('Loan Request Information') }}</h4>
                        </div>
                        <div class="site-card-body">
                            @foreach(json_decode($loan->submitted_data) as $key => $value)
                                <li class="profile-text-data">
                                    <div class="attribute"> {{ $key }}</div>
                                    <div class="value">
                                        @if($value != new stdClass())
                                        @if(file_exists(base_path('assets/'.$value)))
                                            {{-- <img src="{{ asset($value) }}" alt=""/> --}}
                                            <a href="{{ asset($value) }}" class="nav-link p-0" target="_blank">{{ __('Click here to view') }}</a>
                                        @else
                                            <strong>{{ $value }}</strong>
                                        @endif
                                    @endif
                                    </div>
                                </li>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if($loan->status != App\Enums\LoanStatus::Reviewing && $loan->status != App\Enums\LoanStatus::Rejected && $loan->status != App\Enums\LoanStatus::Cancelled)
                <div class="col-xl-12">
                    <div class="site-table table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('SERIAL') }}</th>
                                    <th>{{ __('INSTALLMENT DATES') }}</th>
                                    <th>{{ __('GIVEN DATE') }}</th>
                                    <th>{{ __('DEFERMENT') }}</th>
                                    <th>{{ __('PAID AMOUNT') }}</th>
                                    <th>{{ __('CHARGE') }}</th>
                                    <th>{{ __('FINAL AMOUNT') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($loan->transactions as $transaction)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ safe($transaction->installment_date) }}</td>
                                    <td>{{ safe($transaction->given_date == null ? 'Yet To Pay' : $transaction->given_date) }}</td>
                                    <td>{{ safe($transaction->given_date == null ? 'None' : $transaction->deferment) }}</td>
                                    <td>{{ safe($transaction->given_date == null ? 'None' : $transaction->paid_amount) }}</td>
                                    <td>{{ safe($transaction->given_date == null ? 'None' : $transaction->charge) }}</td>
                                    <td>{{ safe($transaction->given_date == null ? 'None' : $transaction->final_amount) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @elseif($loan->status == App\Enums\LoanStatus::Reviewing)
                    @can('loan-approval')
                        <form action="{{ route('admin.loan.approval.action',$loan->id) }}" method="post">
                            @csrf

                            <div class="action-btns">
                                <button type="submit" name="status" value="running" class="site-btn-sm primary-btn me-2">
                                    <i data-lucide="check"></i>
                                    {{ __('Approve') }}
                                </button>
                                <button type="submit" name="status" value="rejected" class="site-btn-sm red-btn">
                                    <i data-lucide="x"></i>
                                    {{ __('Reject') }}
                                </button>
                            </div>

                        </form>
                    @endcan
                @endif
            </div>
        </div>


    </div>
@endsection
