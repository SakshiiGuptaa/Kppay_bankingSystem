<div class="modal fade" id="addBox" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
                <div class="popup-body-text">
                    <div class="title">{{ __('New Merchants') }}</div>
                    <form action="{{ route('user.merchant_account.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="step-details-form">
                            <div class="row">
                                <!-- Business Name -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="business_name" class="input-label d-block">
                                            {{ __('Business Name') }}<span class="required">*</span>
                                        </label>
                                        <input type="text" class="box-input" id="business_name" name="business_name" placeholder="Enter your business name" required>
                                    </div>
                                </div>

                                <!-- Site URL -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="site_url" class="input-label d-block">
                                            {{ __('Site URL') }}<span class="required">*</span>
                                        </label>
                                        <input type="url" class="box-input" id="site_url" name="site_url" placeholder="https://example.com" required>
                                    </div>
                                </div>
                                
                                <!-- Currency -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="currency" class="input-label d-block">
                                            {{ __('Currency') }}<span class="required">*</span>
                                        </label>
                                        <select class="box-input" id="currency" name="currency">
                                            <option value="XOF">{{ __('XOF') }}</option>
                                            <option value="USD">{{ __('USD') }}</option>
                                            <option value="ZMW">{{ __('ZMW') }}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Merchant Type -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="merchant_type*" class="input-label d-block">
                                            {{ __('Merchant Type') }}<span class="required">*</span>
                                        </label>
                                        <select class="box-input" id="merchant_type" name="merchant_type">
                                            <option value="Standard">{{ __('Standard') }}</option>
                                            <option value="Express">{{ __('Express') }}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Message -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="message" class="input-label d-block">
                                            {{ __('Message for administration') }}
                                        </label>
                                        <textarea class="box-input" style="padding: 10px 15px;" id="message" name="message" placeholder="Enter your message here." required></textarea>
                                    </div>
                                </div>
                                
                                <!-- Business Logo Upload -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="business_logo" class="input-label">
                                            {{ __('Business Logo') }}
                                        </label>
                                        <input type="file" class="box-input" style="padding: 10px 15px;" id="business_logo" name="business_logo" accept=".jpeg,.png,.webp" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-btns mt-3">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i data-lucide="check"></i> {{ __('Create Merchant') }}
                            </button>
                            <button type="button" class="site-btn-sm red-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i data-lucide="x"></i> {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>