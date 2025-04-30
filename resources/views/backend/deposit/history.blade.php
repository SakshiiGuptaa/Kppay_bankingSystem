@extends('backend.deposit.index')
@section('title')
    {{ __('Deposit History') }}
@endsection
@section('deposit_content')
    <div class="col-xl-12 col-md-12">
        <div class="site-card-body table-responsive">
            <div class="site-table table-responsive">
                    <div class="card-header-info" style="display: inline-block; background: rgba(42, 157, 143, 0.25); border-radius: 15px; padding: 6px 16px; border: none; color: #2a9d8f; font-size: 14px; font-weight: 700; float: right; margin:15px;">Total Profits: {{ number_format($totalCharges, 2) }} XOF</div>
                @include('backend.deposit.include.__filter', ['status' => true])
                <table class="table">
                    <thead>
                    <tr>
                        @include('backend.filter.th',['label' => 'Date','field' => 'created_at'])
                        @include('backend.filter.th',['label' => 'User','field' => 'user'])
                        @include('backend.filter.th',['label' => 'Transaction ID','field' => 'tnx'])
                        @include('backend.filter.th',['label' => 'Amount','field' => 'amount'])
                        @include('backend.filter.th',['label' => 'Charge','field' => 'charge'])
                        @include('backend.filter.th',['label' => 'Gateway','field' => 'method'])
                        @include('backend.filter.th',['label' => 'Status','field' => 'status'])
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($deposits as $deposit)
                        <tr>
                            <td>
                                {{ $deposit->created_at }}
                            </td>
                            <td>
                                @include('backend.transaction.include.__user', ['id' => $deposit->user_id, 'name' => $deposit->user->username])
                            </td>
                            <td>{{ safe($deposit->tnx) }}</td>
                            <td style="color:green;">
                                @include('backend.transaction.include.__txn_amount', ['txnType' => $deposit->type->value, 'amount' => $deposit->final_amount, 'pay_currency' => $deposit->pay_currency])
                                <!--{{safe($deposit->final_amount.' '.$deposit->pay_currency) }}-->
                            </td>
                            <td>
                                {{safe($deposit->charge.' '.$deposit->pay_currency) }}
                            </td>
                            <td>
                                {{ safe($deposit->method) }}
                            </td>
                            <td>
                                @include('backend.transaction.include.__txn_status', ['txnStatus' => $deposit->status->value])
                            </td>
                        </tr>
                    @empty
                    <td colspan="7" class="text-center">{{ __('No Data Found!') }}</td>
                    @endforelse
                    </tbody>
                </table>

                {{ $deposits->links('backend.include.__pagination') }}
            </div>
        </div>

    </div>
@endsection

