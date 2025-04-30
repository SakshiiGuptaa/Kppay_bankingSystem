<div class="modal fade" id="addBox" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
                <div class="popup-body-text">
                    <div class="title">{{ __('Create pricing table') }}<p>Customise your pricing table</p></div>
                    <form action="{{ route('user.merchant_account.pricing.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="step-details-form">
                            <div class="row">
                                <!-- Product Name -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="product_name" class="input-label d-block">
                                            {{ __('Products (required)') }}<span class="required">*</span>
                                        </label>
                                        <input type="text" class="box-input" id="product_name" name="product_name" placeholder="Find or add a product..." required>
                                    </div>
                                </div>
                                <a href="#" for="product_name" class="inputs input-label d-block" style="color:blue;">
                                            {{ __('Add product with custom call-to-action button') }}<span class="required">*</span>
                                        </a>

                                <label for="display_settings" class="input-label" style="margin-bottom:20px;">
                                            {{ __(' Display settings') }}
                                        </label>
                                <!-- Add Screenshot Fields -->
                                <!-- Default View -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="default_view" class="input-label">
                                            {{ __('Default View') }}
                                        </label>
                                        <select class="box-input" id="default_view" name="default_view">
                                            <option value="grid">{{ __('Grid') }}</option>
                                            <option value="list">{{ __('List') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Language -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="language" class="input-label">
                                            {{ __('Language') }}
                                        </label>
                                        <select class="box-input" id="language" name="language">
                                            <option value="en">{{ __('English') }}</option>
                                            <option value="es">{{ __('Spanish') }}</option>
                                            <!-- Add more language options as needed -->
                                        </select>
                                    </div>
                                </div>

                                <!-- Background Color -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="background_color" class="input-label">
                                            {{ __('Background Color') }}
                                        </label>
                                        <input type="color" class="box-input" id="background_color" name="background_color" value="#ffffff">
                                    </div>
                                </div>

                                <!-- Button Color -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="button_color" class="input-label">
                                            {{ __('Button Color') }}
                                        </label>
                                        <input type="color" class="box-input" id="button_color" name="button_color" value="#0074d4">
                                    </div>
                                </div>

                                <!-- Font -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="font" class="input-label">
                                            {{ __('Font') }}
                                        </label>
                                        <select class="box-input" id="font" name="font">
                                            <option value="system">{{ __('System Font (Default)') }}</option>
                                            <option value="arial">{{ __('Arial') }}</option>
                                            <option value="verdana">{{ __('Verdana') }}</option>
                                            <!-- Add more fonts as needed -->
                                        </select>
                                    </div>
                                </div>

                                <!-- Button Shape -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="button_shape" class="input-label">
                                            {{ __('Button Shape') }}
                                        </label>
                                        <select class="box-input" id="button_shape" name="button_shape">
                                            <option value="rounded">{{ __('Rounded') }}</option>
                                            <option value="square">{{ __('Square') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Highlight Product -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="highlight_product" class="input-label">
                                            {{ __('Highlight Product') }}
                                        </label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="highlight_product" name="highlight_product">
                                            <label class="form-check-label" for="highlight_product">{{ __('Yes') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="action-btns mt-3">
                                    <button type="submit" class="site-btn-sm primary-btn me-2">
                                        <i data-lucide="check"></i> {{ __('Add Product') }}
                                    </button>
                                    <button type="button" class="site-btn-sm red-btn" data-bs-dismiss="modal" aria-label="Close">
                                        <i data-lucide="x"></i> {{ __('Cancel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
