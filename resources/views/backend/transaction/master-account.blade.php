@extends('backend.layouts.app')
@section('title')
    {{ __('Master Account') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title"></div>
        <div class="container-fluid">
            <div class="row">

                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h4 class="title">{{ __('Master Account') }}</h4>
                            <div class="card-header-info">{{ __('Total Amount Available:') }}  XOF </div>
                        </div>
                        <div class="site-card-body p-0">
                            <div class="site-table table-responsive">
                                @include('backend.transaction.include.__filter', ['status' => true, 'type' => true ])
                                <table class="table">
                                    <thead>
                                    <tr>
                                        @include('backend.filter.th',['label' => 'Id','field' => 'id'])
                                        @include('backend.filter.th',['label' => 'Date/Time','field' => 'created_at'])
                                        @include('backend.filter.th',['label' => 'Account No.','field' => 'account_number'])
                                        @include('backend.filter.th',['label' => 'Type','field' => 'type'])
                                        @include('backend.filter.th',['label' => 'From','field' => 'from'])
                                        @include('backend.filter.th',['label' => 'Amount','field' => 'amount'])
                                        @include('backend.filter.th',['label' => 'Currency','field' => 'currency'])
                                        @include('backend.filter.th',['label' => 'View Details','field' => 'view'])
                                    </tr>
                                    </thead>
                                     <tbody>
                                        @foreach ($masterAccounts as $account)
                                            <tr>
                                                <td>{{ $account->id }}</td>
                                                <td>{{ $account->datetime }}</td>
                                                <td>{{ $account->account_no }}</td>
                                                <td>{{ ucfirst($account->type) }}</td>
                                                <td>{{ ucfirst($account->from) }}</td>
                                                <td>{{ number_format($account->amount, 2) }}</td>
                                                <td>{{ $account->currency }}</td>
                                                <td>
                                                    @include('backend.transaction.include.__action' , ['id' => $account->id])
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                              
                            </div>
                                <!-- Modal for Pending KYC Details -->
                                @can('kyc-action')
                                @include('backend.kyc.include.__details_modal')
                                @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";
            let loader = '<div class="text-center"><img src="{{ asset('front/images/loader.gif') }}" width="100"><h5>{{ __('Please wait') }}...</h5></div>';

            $('body').on('click', '#action-master-account', function (e) {
                "use strict";
                e.preventDefault()
                $('#master-account-action-data').html(loader);

                var id = $(this).data('id');

                console.log(id);

                var url = '{{ route("admin.kyc.action",":id") }}';
                url = url.replace(':id', id);
                $.get(url, function (data) {
                    $('#kyc-action-data').html(data);
                    imagePreview()
                })


                $('#kyc-action-modal').modal('toggle')
            })

        })(jQuery);


    </script>
@endsection