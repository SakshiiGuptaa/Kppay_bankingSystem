@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Now') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <div class="site-card">
                <div class="site-card-header">
                    <div class="title">{{ __('Withdraw Money') }}</div>
                    <div class="card-header-links">
                        <div class="d-flex">
                            <a href="{{ route('user.withdraw.account.index') }}" class="card-header-link"
                            ><i data-lucide="alert-circle"></i>{{ __('Withdraw Account') }}</a
                            >
                            <a href="{{ route('user.withdraw.log') }}" class="card-header-link">
                                <i data-lucide="alert-circle"></i>
                                {{ __('Withdraw History') }}
                            </a>
                        </div>
                    </div>
                </div>
                <form action="{{ route('user.withdraw.now') }}" method="post">
                    @csrf
                    <div class="site-card-body">
                        <div class="step-details-form mb-4">

                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="inputs">
                                        <label for="" class="input-label">
                                            {{ __('Select') }}
                                            <span class="required">*</span>
                                        </label>
                                        <select
                                            name="withdraw_account"
                                            class="box-input select2-basic-active"
                                            id="withdrawAccountId"
                                        >
                                            <option selected disabled>{{ __('Select Account') }}</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->method_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-info-text processing-time">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="inputs">
                                        <label for="" class="input-label">
                                            {{ __('Enter Phone Number') }}
                                            <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control withdrawnumber" name="phone_number" placeholder="Enter Phone Number"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="inputs">
                                        <label for="" class="input-label">
                                            {{ __('Enter Amount') }}
                                            <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control withdrawAmount" name="amount"  placeholder="Enter Amount"/>
                                            <span class="input-group-text withdraw-currency"></span>
                                        </div>
                                        <div class="input-info-text withdrawAmountRange">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="site-card">
                            <div class="site-card-header">
                                <div class="title-small">{{ __('Summary:') }}</div>
                            </div>
                            <div class="site-card-body p-0 overflow-x-auto">
                                <div class="site-custom-table site-custom-table-sm">
                                    <div class="contents">
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Amount:') }}</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="fw-bold withdrawAmountp"></div>
                                            </div>
                                        </div>
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Charge:') }}</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="red-color fw-bold withdrawFee"></div>
                                            </div>
                                        </div>
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Payment Method:') }}</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="fw-bold method">

                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="site-table-list">-->
                                        <!--    <div class="site-table-col">-->
                                        <!--        <div class="trx fw-bold">-->
                                        <!--            {{ __('Payment Method Logo:') }}-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--    <div class="site-table-col">-->
                                        <!--        <div class="fw-bold" id="logo">-->
                                        <!--            <img class="table-icon" src="" alt=""/>-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <!--<div class="site-table-list">-->
                                        <!--    <div class="site-table-col">-->
                                        <!--        <div class="fw-bold">{{ __('Conversion Rate:') }}</div>-->
                                        <!--    </div>-->
                                        <!--    <div class="site-table-col">-->
                                        <!--        <div class="fw-bold conversion-rate"></div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Total:') }}</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="fw-bold pay-amount"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button
                            @if(auth()->user()->passcode !== null && setting('withdraw_passcode_status'))
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#passcode"
                            @else
                            type="submit"
                            @endif
                            class="site-btn polis-btn"
                        >
                            <i data-lucide="download"></i>{{ __('Withdraw Money') }}
                        </button>
                        <div id="loader" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:1000;">
                            <div class="text-center" style="display:flex; justify-content:center; align-items:center; background-color:white;">
                                <h2>{{ __('Processing') }}</h2>
                                <img src="{{ asset('front/gif/loader.gif') }}" alt="Loading..." style="height:130px;">
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->passcode !== null && setting('withdraw_passcode_status'))
                    <div class="modal fade" id="passcode" tabindex="-1" aria-labelledby="passcodeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content site-table-modal">
                                <div class="modal-body popup-body">
                                    <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <i data-lucide="x"></i>
                                    </button>
                                    <div class="popup-body-text">
                                        <div class="title">{{ __('Confirm Your Passcode') }}</div>
                                        <div class="step-details-form">
                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12 col-md-12">
                                                    <div class="inputs">
                                                        <label for="" class="input-label">{{ __('Passcode') }}<span class="required">*</span></label>
                                                        <input type="password" class="box-input" name="passcode" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="action-btns">
                                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                                <i data-lucide="check"></i>
                                                {{ __('Confirm') }}
                                            </button>
                                            <button type="button" class="site-btn-sm red-btn" data-bs-dismiss="modal" aria-label="Close">
                                                <i data-lucide="x"></i>
                                                {{ __('Close') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        "use strict";

        // Select 2 activation
        $(".select2-basic-active").select2({
            minimumResultsForSearch: Infinity,
        });

        // nice select
        $(".add-beneficiary").niceSelect();
        $(".edit-beneficiary").niceSelect();

        var currency = @json($currency);
        var info = [];

        $('.method').hide();

        $("#withdrawAccountId").on('change', function (e) {
            e.preventDefault();

            // $('.selectDetailsTbody').children().not(':first', ':second').remove();
            var accountId = $(this).val()
            var amount = $('.withdrawAmount').val();

            if (!isNaN(accountId)) {
                var url = '{{ route("user.withdraw.details",['accountId' => ':accountId', 'amount' => ':amount']) }}';
                url = url.replace(':accountId', accountId,);
                url = url.replace(':amount', amount);

                $.get(url, function (data) {
                    // $(data.html).insertAfter(".detailsCol");
                    info = data.info;
                    $('.withdrawAmountRange').text(info.range)
                    $('.processing-time').text(info.processing_time);
                    $('.method').html('<span class="type site-badge badge-primary">'+info.name+'</span>');
                    $('.method').show();
                    $('#logo').html(info.logo);
                })
            }

        })
        
        $(".withdrawAmount").on('keyup', function (e) {
            "use strict";
            e.preventDefault();
            
            var amount = parseFloat($(this).val()) || 0;
        
            // Calculate the charge
            var charge = info.charge_type === 'percentage'
                ? (amount * (info.charge || 0) / 100).toFixed(2) // Percentage-based charge
                : parseFloat(info.charge || 0);                 // Fixed charge
        
            // Convert charge back to a number (if it's a string due to .toFixed)
            charge = parseFloat(charge);
        
            // Calculate the total amount (amount + charge)
            var totalAmount = amount + charge;
        
            // Get the exchange rate and pay currency
            var rate = parseFloat(info.rate) || 1;
            var payCurrency = info.pay_currency || 'N/A';
        
            // Update the DOM with calculated values
            $('.withdrawAmountp').text(amount + ' ' + payCurrency);
            $('.withdrawFee').text('-' + charge.toFixed(2) + ' ' + payCurrency);
            $('.conversion-rate').text('1 ' + currency + ' = ' + rate + ' ' + payCurrency);
            $('.pay-amount').text((totalAmount).toFixed(2) + ' ' + payCurrency); // Amount + charge
            $('.withdrawAmountRange').text(info.range || '');
            $('.withdraw-currency').text(payCurrency);
        });


    </script>
    
    <script>

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="{{ route('user.withdraw.now') }}"]');
    const loader = document.getElementById('loader');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        loader.style.display = 'block';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            loader.style.display = 'none';

            if (data.status === 'success') {
                const finalMessage = `Transfer Successful!\nStatus: ${data.status}\nMessage: ${data.message}\nTransaction ID: ${data.transaction_id}`;
                alert(finalMessage);
                console.log(finalMessage);
            } else if (data.status === 'failed') {
                alert(`Failed: ${data.message}`);
                
            } else {
                alert(`Unknown Status: ${data.status}\nMessage: ${data.message}`);
                console.log("Status:", data.status);
            }
        })
        .catch(error => {
            loader.style.display = 'none';
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>

@endsection
