@extends('frontend::layouts.user')

@section('title')
    {{ __('Fund Transfer') }}
@endsection

@section('content')
<div class="row justify-content-center">

    <div class="col-xl-6 col-lg-8 col-md-10 col-12">
        <div class="transaction-success-card p-4 rounded shadow-lg text-center" >
            <span id="printable-card">
            <!-- Success Icon -->
            <div class="success-icon mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 50px;"></i>
            </div>

            <!-- Payment Details -->
            <h4 class="font-weight-bold text-dark mb-3">
                {{ $message }}
            </h4>
            <h2 class="text-primary mb-4">
                {{ $responseData['currency'] }} {{ number_format($responseData['amount'], 2) }}
            </h2>

            <!-- From and To Details -->
            <div class="text-left mb-3">
                <p><strong>{{ __('From:') }}</strong> {{ $responseData['account'] }}</p>
                <p><strong>{{ __('To:') }}</strong> {{ $responseData['account'] }}</p>
            </div>

            <!-- Additional Information -->
            <div class="text-left mb-3">
                <p><strong>{{ __('Payment Mode:') }}</strong> {{ $responseData['account'] }}</p>
                <p><strong>{{ __('Payment Date:') }}</strong></p>
                <p><strong>{{ __('Payment Time:') }}</strong></p>
            </div>

            <!-- Transaction ID -->
            <p class="text-muted mb-3">
                <strong>{{ __('Transaction ID:') }}</strong> {{ $responseData['tnx'] }}
            </p>

            <div class="site-logo">
                <a href="https://kppay.fr" class="logo"><img src="https://kppay.fr/assets/global/images/BsTOZapyTlAPtxZeA2Fx.png" style="height:auto;width:150px;max-width:none" alt="Dhruv Patil"></a>
            </div>
            </span>
 
            <!-- Button -->
            <a href="{{ route('user.fund_transfer.index') }}" class="btn btn-primary">
                {{ __('Transfer Again') }}
            </a>
            <button class="btn btn-outline-secondary" onclick="printCard()" style="margin-left:5px;">
                <i class="fas fa-print"></i> {{ __('Print') }}
            </button>
        </div>
    </div>
</div>

<style>
    .transaction-success-card {
    background-color: #ffffff;
    background-color: #eeeeee;
    border: 1px solid #e3e3e3;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.success-icon i {
    color: #28a745; /* Green for success */
    font-size: 50px;
}

.transaction-success-card h4 {
    font-size: 24px;
    color: #333333;
}

.transaction-success-card h2 {
    font-size: 32px;
    color: #007bff; /* Primary color */
    margin-bottom: 20px;
}

.transaction-success-card p {
    font-size: 16px;
    color: #6c757d; /* Muted text color */
    margin-bottom: 10px;
}

.transaction-success-card .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
}

@media print {
    body * {
        visibility: hidden;
    }
    #printable-card, #printable-card * {
        visibility: visible;
    }
    #printable-card {
        position: absolute;
        left: 0;
        top: 80px;
        height:auto;
        width: 100%;
    }
}
</style>
<!-- Add Print Functionality -->
<script>
function printCard() {
    window.print();
}
</script>

@endsection
