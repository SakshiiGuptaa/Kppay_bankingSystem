@if(request('tab') == 'card')
<div @class(['tab-pane fade', 'show active'=> request('tab') == 'card']) 
     id="pills-loan" 
     role="tabpanel" 
     aria-labelledby="pills-loan-tab">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Virtual Card') }}</h4>
                </div>
                <div class="site-card-body">
    <div class="row">
        @if($virtualCards->isEmpty())
            <p class="text-center">{{ __('No Virtual Cards Available') }}</p>
        @else
            @foreach($virtualCards as $card)
                <div class="col-xl-6 col-lg-12 col-md-12 col-12">
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
                            <span style="margin-left: 120px;">
                                <img src="{{ asset($card->card_type === 'visa_card' ? '/global/images/visa-logo-black-and-white.png' : '/global/images/mastercard-logo.png') }}" 
                                     alt="{{ $card->card_type === 'visa_card' ? 'Visa Logo' : 'Mastercard Logo' }}" 
                                     style="width: 60px; height: auto;">
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

            </div>
        </div>
    </div>
</div>
@endif
