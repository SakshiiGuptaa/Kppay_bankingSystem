@extends('frontend::layouts.user')
@section('title')
{{ __('Deposit Failed') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <div class="transaction-success-block success">
                <div class="icon"><i data-lucide="droplet"></i></div>
                <div class="headding">{{ request('amount') }} XOF Deposit Failed</div>
                <div class="text">The amount has not been added into your account</div>
                <div class="trx"></div>
                <a href="{{ route('user.deposit.amount')}}" class="site-btn polis-btn">{{ __('Deposit Again') }}</a>
            </div>
        </div>
    </div>
@endsection

