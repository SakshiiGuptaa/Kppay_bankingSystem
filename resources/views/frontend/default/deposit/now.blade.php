@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Now') }}
@endsection
@section('content')
    <form action="{{ route('user.deposit.now') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <div class="site-card">
                    <div class="site-card-header">
                        <div class="title">{{ __('Add Money') }}</div>
                        <div class="card-header-links">
                            <a href="{{ route('user.deposit.log') }}" class="card-header-link"><i data-lucide="alert-circle"></i>{{ __('Deposit History') }}</a>
                        </div>
                    </div>
                    <div class="site-card-body">
                        <div class="step-details-form mb-4">
                            <div class="row">
                                
                                @if(setting('multiple_currency','permission'))
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="inputs">
                                        <label for="" class="input-label">{{ __('Select Wallet') }}<span class="required">*</span></label>
                                        <select name="wallet_type" class="box-input" id="walletSelect">
                                            <option value="" disabled selected>--{{ __('Select Wallet') }}--</option>
                                            <option value="default" data-currency="{{ setting('site_currency') }}">{{ __('Default Wallet') }}</option>
                                            @foreach ($wallets as $wallet)
                                                <option value="{{ $wallet->id }}" @selected($code == $wallet->currency?->code) data-currency="{{ $wallet->currency?->code }}">{{ $wallet?->currency?->name }} ({{ $wallet?->currency?->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div @class([
                                    'col-xl-3 col-lg-3 col-md-3' => setting('multiple_currency','permission'),
                                    'col-xl-6 col-lg-6 col-md-6' => !setting('multiple_currency','permission')
                                ])>
                                    <div class="inputs">
                                        <label for="" class="input-label">{{ __('Select Gateway') }}<span class="required">*</span></label>
                                        <select name="gateway_code" class="box-input deposit-methods" id="gatewaySelect">
                                            <option value="" disabled selected>--{{ __('Select Gateway') }}--</option>
                                            @if(!setting('multiple_currency','permission'))
                                                @foreach ($gateways as $gateway)
                                                    <option data-logo="{{ asset($gateway->logo) }}" value="{{ $gateway->gateway_code }}">{{ $gateway->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="input-info-text charge"></div>
                                    </div>
                                </div>
                                
                                <!-- Phone Number Input Field (Hidden by Default) -->
                                <div id="phoneInputField" class="col-xl-3 col-lg-3 col-md-3" style="display: none;">
                                    <div class="inputs">
                                        <label for="phoneNumber" class="input-label">{{ __('Enter Phone Number') }}<span class="required">*</span></label>
                                        <input type="text" name="phone_number" id="phoneNumber" class="box-input" placeholder="{{ __('Enter Phone Number') }}">
                                    </div>
                                </div>
                                
                                <div @class([
                                    'col-xl-3 col-lg-3 col-md-3' => setting('multiple_currency','permission'),
                                    'col-xl-6 col-lg-6 col-md-6' => !setting('multiple_currency','permission')
                                ])>
                                    <div class="inputs">
                                        <label for="" class="input-label">{{ __('Enter Amount:') }}<span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="amount" id="amount" required>
                                            <!--<span class="input-group-text" id="basic-addon1">{{ $currency }}</span>-->
                                            <span class="input-group-text" id="basic-addon1"></span>
                                        </div>
                                        <div class="input-info-text min-max"></div>
                                    </div>
                                </div>

                                <div class="row manual-row">

                                </div>
                            </div>
                        </div>
                        <div class="site-card">
                            <div class="site-card-header">
                                <div class="title-small">{{ __('Review Details:') }}</div>
                            </div>
                            <div class="site-card-body p-0 overflow-x-auto">
                                <div class="site-custom-table site-custom-table-sm">
                                    <div class="contents">
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Amount:') }}</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="fw-bold amount"> </div>
                                            </div>
                                        </div>
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Charge:') }}</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="red-color fw-bold charge2"></div>
                                            </div>
                                        </div>
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Payment Method:') }}</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="fw-bold method"></div>
                                            </div>
                                        </div>
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Payment Method Logo') }}:</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="fw-bold" id="logo"><img class="table-icon" src="" alt=""></div>
                                            </div>
                                        </div>
                                        <!--<div class="site-table-list">-->
                                        <!--    <div class="site-table-col">-->
                                        <!--        <div class="fw-bold">{{ __('Conversion Rate') }}:</div>-->
                                        <!--    </div>-->
                                        <!--    <div class="site-table-col">-->
                                        <!--        <div class="fw-bold conversion-rate"></div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Total') }}:</div>
                                            </div>
                                            <div class="site-table-col">
                                                <div class="fw-bold total"></div>
                                            </div>
                                        </div>
                                        <div class="site-table-list">
                                            <div class="site-table-col">
                                                <div class="trx fw-bold">{{ __('Payable Amount') }}:</div>
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
                            @if(auth()->user()->passcode !== null && setting('deposit_passcode_status'))
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#passcode"
                            @else
                            type="submit"
                            @endif
                            class="site-btn polis-btn"
                        >
                            {{ __('Proceed to payment') }}
                        </button>
                        <div id="loader" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:1000;">
                            <div class="text-center" style="display:flex; justify-content:center; align-items:center; background-color:white;">
                                <h2>{{ __('Processing Payment') }}</h2>
                                <img src="{{ asset('front/gif/loader.gif') }}" alt="Loading..." style="height:130px;">
                            </div>
                        </div>

                    </div>
                </div>
                @if(auth()->user()->passcode !== null && setting('deposit_passcode_status'))
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
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
        "use strict"

        // Select 2 activation
        function formatState(state) {
            if (!state.id) {
                return state.text;
            }

            var $state = $(
                '<span><img src="' + $(state.element).data('logo') + '" class="img-icon" /> ' + state.text + '</span>'
            );

            return $state;
        };
        
        initGatewaySelect();
        function initGatewaySelect()
        {
            $('#gatewaySelect').select2({
                templateResult: formatState,
                templateSelection : formatState,
                minimumResultsForSearch: Infinity,
            });
        }

        $('#walletSelect').select2({
            minimumResultsForSearch: Infinity,
        });

        var globalData;
        var currency = @json($currency);

        $('#walletSelect').on('change',function(){
            "use strict";
            getGateways();
        });


        @if(setting('multiple_currency','permission'))
            getGateways();
            function getGateways()
            {
                var currency = $('#walletSelect').find('option:selected').data('currency');
                var url = '{{ route("user.deposit.get.gateways",":currency") }}';
                url = url.replace(':currency', currency);

                $.get(url,function(response){
                    $('#gatewaySelect').html(response.options);
                    initGatewaySelect();
                });
            }

        @endif

        $("#gatewaySelect").on('change', function (e) {
            "use strict"
            e.preventDefault();
            $('.manual-row').empty();

            var code = $(this).val()
            var url = '{{ route("user.deposit.gateway",":code") }}';
            url = url.replace(':code', code);

            $.get(url, function (data) {

                globalData = data;

                if (data.currency === currency){
                    $('.conversion').addClass('hidden');
                }else {
                    $('.conversion').removeClass('hidden'); 
                }

                $('.charge').text('Charge ' + data.charge + ' ' + (data.charge_type === 'percentage' ? ' % ' :  data.currency))
                $('.conversion-rate').text('1' +' '+ currency + ' = ' + data.rate +' '+ data.currency);
                
                // Below id is for the currency in the amount field
                $('#basic-addon1').text(data.currency);

                $('.method').html('<span class="type site-badge badge-primary">'+data.name+'</span>')
                $('.min-max').text('Minimum ' + data.minimum_deposit + ' ' + data.currency + ' and ' + 'Maximum ' + data.maximum_deposit + ' ' + data.currency)
                $('#logo').html(`<img class="table-icon" src='${data.gateway_logo}'>`);
                var amount = $('#amount').val()

                if (Number(amount) > 0) {
                    $('.amount').text((amount +' '+ data.currency));
                    var charge = data.charge_type === 'percentage' ? calPercentage(amount, data.charge) : data.charge
                    $('.charge2').text(charge + ' ' + data.currency)
                    $('.total').text((Number(amount) + Number(charge)) + ' ' + currency)
                }

                if (data.credentials !== undefined) {
                    $('.manual-row').append(data.credentials)
                    imagePreview()
                }

            });

   // Initialize the default selected wallet currency
    var wallet_select_currency = $('#walletSelect option:selected').data('currency');

    // Listen for changes in the wallet dropdown
    $('#walletSelect').on('change', function () {
        wallet_select_currency = $(this).find(':selected').data('currency'); // Get the selected currency
        updateDisplayedCurrency();
    });

    // Listen for changes in the amount input field
    $('#amount').on('keyup', function () {
        var amount = $(this).val();
        $('.amount').text(amount + ' ' + wallet_select_currency);

        var charge = globalData.charge_type === 'percentage'
            ? calPercentage(amount, globalData.charge)
            : globalData.charge;

        $('.charge2').text(charge + ' ' + wallet_select_currency);

        var total = (Number(amount) + Number(charge));
        $('.total').text(total + ' ' + wallet_select_currency);

        var payTotal = (total / globalData.rate).toFixed(2) + ' ' + currency;
        $('.pay-amount').text(payTotal);
    });
        // Function to update displayed currency in relevant areas
    function updateDisplayedCurrency() {
        var amount = $('#amount').val();
        $('.amount').text(amount + ' ' + wallet_select_currency);
        $('.charge2').text(globalData.charge + ' ' + wallet_select_currency); // Assuming no recalculation here
        $('.total').text((Number(amount) + globalData.charge) + ' ' + wallet_select_currency); // Example adjustment
    }

        //  $('#amount').on('keyup', function (e) {
        //         "use strict"
        //         var amount = $(this).val()
        //         $('.amount').text((amount +' '+ currency));

        //         var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge
        //         $('.charge2').text(charge + ' ' + currency)

        //         var total = (Number(amount) + Number(charge));

        //         $('.total').text(total + ' ' + currency)
        //         var payTotal = total * globalData.rate +' '+ globalData.currency;
        //         $('.pay-amount').text(payTotal)
        //     })

        });
    </script>
    <!--javascript code to show phone number field after selecting currency-->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const gatewaySelect = $('#gatewaySelect');
        const phoneInputField = document.getElementById('phoneInputField');
    
        gatewaySelect.on('select2:select', function (e) {
            const selectedValue = e.params.data.id;
            // console.log('Selected Gateway:', selectedValue); // Debug log
    
            if (selectedValue === 'mtn-xaf') {
                phoneInputField.style.display = 'block';
            } else {
                phoneInputField.style.display = 'none';
            }
        });
    });
    </script>
    <!--ajax call for status and callback-->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action="{{ route('user.deposit.now') }}"]');
        const loader = document.getElementById('loader');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission
            loader.style.display = 'block'; // Show the loader

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'success') {
                    loader.style.display = 'none'; // Hide the loader
                        // console.log("Transaction ID:", data.transaction_id);
                        alert(data.message);
                        // Redirect or update the page
                    } else if (data.status === 'pending') {
                        // alert(data.message);
                        console.log(data.message);
                        // console.log("Reference ID:", data.transaction_id);
                         pollTransactionStatus(data.transaction_id,data.userwallet_id); // Pass transaction_id for polling
                        // Handle pending case
                    }else if (data.status === 'failed') {
                        loader.style.display = 'none'; // Hide the loader
                    
                        // Redirect to the failed page with the amount as a query parameter
                        const failedUrl = "{{ route('user.deposit.failed') }}?amount=" + encodeURIComponent(data.amount);
                        window.location.href = failedUrl;
                    } else {
                        alert('Unknown response from server.');
                    }
                })
                .catch((error) => {
                    loader.style.display = 'none'; // Hide the loader
                    console.error(error);
                    alert('An error occurred. Please try again.');
                });
        });
    });
    
    function pollTransactionStatus(transactionId,userwalletId) {
        const loader = document.getElementById('loader');
    setTimeout(function () {
        fetch('{{ route('user.deposit.status') }}', {
            method: 'POST',
            body: JSON.stringify({ transaction_id: transactionId,
                userwallet_id: userwalletId }), // Pass the refId, userWalletId
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'pending') {
                    // alert(data.message);
                    console.log(data.message); // Log pending status
                    pollTransactionStatus(transactionId,userwalletId); // Continue polling
                } else if (data.status === 'success') {
                    loader.style.display = 'none'; // Hide the loader
                    // alert(`Payment successful. Wallet updated. New balance: ${data.updated_balance}`);
                    
                    const successUrl = "{{ route('user.deposit.success') }}?amount=" + encodeURIComponent(data.amount) + 
                   "&updated_balance=" + encodeURIComponent(data.updated_balance);
                    window.location.href = successUrl;
                    
                    // Redirect or update the page
                } else if (data.status === 'failed') {
                    loader.style.display = 'none'; // Hide the loader
                    // Redirect to the failed page with the amount as a query parameter
                    const failedUrl = "{{ route('user.deposit.failed') }}?amount=" + encodeURIComponent(data.amount);
                    window.location.href = failedUrl;
                    // alert(data.message);
                    // Handle failed case
                } else {
                    alert('Unknown response from server.');
                }
            })
            .catch((error) => {
                console.error(error);
                alert('An error occurred while checking transaction status.');
            });
    }, 5000); // 5-second delay
}
</script>


@endsection
