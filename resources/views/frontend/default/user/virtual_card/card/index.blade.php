@php use App\Enums\TxnStatus; @endphp
@extends('frontend::layouts.user')
@section('title')
{{ __('Virtual Card') }}
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('front/css/daterangepicker.css') }}">
@endpush
@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
        <div class="site-card">
            <div class="site-card-header d-flex justify-content-between">
                <div class="title-small">{{ __('Cards') }}</div>

                @if(setting('card_creation','permission'))
                <a href="javascript:void(0)" class="site-btn-sm primary-btn" data-bs-toggle="modal"
                    data-bs-target="#createWalletModal">
                    <i data-lucide="plus-circle"></i>
                    {{ __('Add new card') }}
                </a>
                @endif
            </div>
            <div class="site-card-body">
                            
                <div class="row">
        @if($cards->isEmpty())
            <p class="text-center">{{ __('No Virtual Cards Available') }}</p>
        @else
            @foreach($cards as $card)
                <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                    <div 
                        style="background: {{ $card->card_type === 'visa_card' ? 
                            'linear-gradient(42deg, #000000 -1.07%, #1a1a1a 50%, #ac9b50 85%, #decb60 95%)' : 
                            'linear-gradient(42deg, #0a0a0a -1.07%, #262626 50%, #6e2c70 85%, #a350ac 95%)' }};
                        color: white; 
                        padding: 25px;
                        border-radius: 10px;
                        margin-bottom: 25px;
                        min-height: 200px;
                        position: relative;
                        overflow: hidden;
                        min-height: 270px;"
                    >
                        @php
                            $height = setting('site_logo_height','global') == 'auto' ? 'auto' : setting('site_logo_height','global').'px';
                            $width = setting('site_logo_width','global') == 'auto' ? 'auto' : setting('site_logo_width','global').'px';
                        @endphp
                        <div class="site-logo">
                            <a href="{{route('home')}}" class="logo">
                                <img src="{{ asset(setting('site_logo','global')) }}" style="height:{{ $height }};width:{{ $width }};max-width:none" alt="{{ auth()->user()->full_name }}">
                            </a>
                        </div>
        
                        <div class="card-num">
                            <h5 style="font-size: 26px;font-weight: 700; color: #d4af37; text-shadow: -1px -1px 2px white, -2px -2px 1px #555555; letter-spacing: 2px; font-family: 'Arial', sans-serif;">{{ $card->card_number }}</h5>
                        </div>
                        
                        <div style="display: flex; justify-content: space-evenly; width: 100%; margin-top: 10px; font-family: Arial, sans-serif;">
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <span style="font-size: 12px; letter-spacing: 1px; color: #cfc9b6; margin-bottom: 2px;">VALID</span>
                                <small style="font-size: 10px; color: #b4ae9c; margin-bottom: 5px;">FROM</small>
                            </div>
                            <strong style="font-size: 18px; font-weight: 700;  color: #d4af37;">02/23</strong>
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <span style="font-size: 12px; letter-spacing: 1px; color:#cfc9b6; margin-bottom: 2px;">VALID</span>
                                <small style="font-size: 10px; color: #b4ae9c; margin-bottom: 5px;">THRU</small>
                            </div>
                            <strong style="font-size: 18px; font-weight: 700; color: #d4af37;">{{ $card->card_expiry }}</strong>
                        </div>
        
                        <div class="card-holder-name">
                            <span style="font-size: 22px;font-weight: 700; color: #d4af37; text-shadow: -1px -1px 2px white, -2px -2px 1px #555555; letter-spacing: 2px; font-family: 'Arial', sans-serif;  margin-top: 3px;">{{ $card->card_name }}</span>
                            <span style="margin-left: 30px;">
                                <img src="{{ asset($card->card_type === 'visa_card' ? '/global/images/visa-logo-black-and-white.png' : '/global/images/master-card4.png') }}" 
                                     alt="{{ $card->card_type === 'visa_card' ? 'Visa Logo' : 'Mastercard Logo' }}" 
                                     style="width: 60px; height: auto;">
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>


                <div class="modal fade" id="createWalletModal" tabindex="-1" aria-labelledby="openTicketModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content site-table-modal">
                            <div class="modal-body popup-body"> <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close"> <i data-lucide="x"></i> </button>
                                <div class="popup-body-text">
                                    <div class="title">{{ __('New card information') }}</div>

                                    <form action="{{ route('user.virtual_card.store') }}" method="post">
                                        @csrf
                                    
                                        <div class="step-details-form" id="new_cardholder_part">
                                            <div class="row">
                                                <!-- Name on Card -->
                                                <div class="col-xl-12 col-md-12 inputs">
                                                    <label class="form-label">{{ __('Name on card') }} <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="card_name" class="form-control" required>
                                                    </div>
                                                </div>
                                    
                                                <!-- Type of Card -->
                                                <div class="col-xl-12 col-lg-12 col-md-12">
                                                    <div class="inputs">
                                                        <label class="form-label">{{ __('Type of card') }} <span class="required">*</span></label>
                                                        <select name="card_type" class="country-select-input box-input page-count" required>
                                                            <option value="" selected>{{ __('Select a card type') }}</option>
                                                            <option value="master_card">{{ __('Master Card') }}</option>
                                                            <option value="visa_card">{{ __('Visa Card') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                    
                                                <!-- Card Number -->
                                                <div class="col-xl-12 col-md-12 inputs">
                                                    <label class="form-label">{{ __('Card number') }} <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="card_number" class="form-control" required>
                                                    </div>
                                                </div>
                                    
                                                <!-- Card Expiry -->
                                                <div class="col-xl-12 col-md-12 inputs">
                                                    <label class="form-label">{{ __('Card expiry') }} <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY" required>
                                                    </div>
                                                </div>
                                    
                                                <!-- CVV Code -->
                                                <div class="col-xl-12 col-md-12 inputs">
                                                    <label class="form-label">{{ __('CVV code') }} <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="card_cvv" class="form-control" maxlength="3" required>
                                                    </div>
                                                </div>
                                    
                                                <!-- Country -->
                                                <div class="col-xl-12 col-lg-12 col-md-12">
                                                    <div class="inputs">
                                                        <label for="" class="input-label">{{ __('Country') }}<span class="required">*</span></label>
                                                        <select class="country-select-input box-input page-count" name="country" required>
                                                            <option selected value="">{{ __('Select Country') }}</option>
                                                            @foreach ($countries_list as $country)
                                                                <option value="{{ $country['code'] }}">{{ $country['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="action-btns">
                                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                                <i data-lucide="check"></i> {{ __('Add new card') }}
                                            </button>
                                    
                                            <button type="button" class="site-btn-sm red-btn" data-bs-dismiss="modal" aria-label="Close">
                                                <i data-lucide="x"></i> {{ __('Close') }}
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $('#existing_one').is(':checked') ? $('#new_cardholder_part').addClass('d-none') : $('#existing_cardholder_part').addClass('d-none');

        function changeCardholderType(type){
            if(type == 'existing_one'){
                $('#existing_cardholder_part').removeClass('d-none');
                $('#new_cardholder_part').addClass('d-none');
            }else{
                $('#existing_cardholder_part').addClass('d-none');
                $('#new_cardholder_part').removeClass('d-none');
            }
        }
    </script>
@endpush
