<div class="modal fade" id="addBox" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
                <div class="popup-body-text">
                    <div class="title">{{ __('Add a Product') }}</div>
                    <form action="{{ route('user.merchant_account.product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="step-details-form">
                            <div class="row">
                                <!-- Product Name -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="product_name" class="input-label d-block">
                                            {{ __('Name (required)') }}<span class="required">*</span>
                                        </label>
                                        <input type="text" class="box-input" id="product_name" name="product_name" required>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="product_description" class="input-label d-block">
                                            {{ __('Description') }}
                                        </label>
                                        <textarea class="box-input" id="product_description" name="product_description"></textarea>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="product_image" class="input-label">
                                            {{ __('Image') }}
                                        </label>
                                        <input type="file" class="box-input" id="product_image" name="product_image" accept=".jpeg,.png,.webp" required>
                                    </div>
                                </div>

                                <!-- Product Tax Code -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="product_tax_code" class="input-label">
                                            {{ __('Product Tax Code') }}
                                        </label>
                                        <select class="box-input" id="product_tax_code" name="product_tax_code">
                                            <option value="general-services">{{ __('General - Services') }}</option>
                                            <option value="specific-services">{{ __('Specific - Services') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Add a More Options button -->
                                <div class="col-xl-12 col-lg-12 col-md-12" style="margin-bottom:12px;">
                                    <button type="button" id="more-options-toggle" class="site-btn-sm primary-btn">
                                        {{ __('More Options') }}
                                    </button>
                                </div>
                                
                                <!-- More Options Section -->
                                <div id="more-options-section" style="display: none;">
                                
                                    <!-- Statement Descriptor -->
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="inputs">
                                            <label for="statement_descriptor" class="input-label">
                                                {{ __('Statement Descriptor') }}
                                            </label>
                                            <input type="text" class="box-input" id="statement_descriptor" name="statement_descriptor">
                                        </div>
                                    </div>
                                
                                    <!-- Unit Label -->
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="inputs">
                                            <label for="unit_label" class="input-label">
                                                {{ __('Unit Label') }}
                                            </label>
                                            <input type="text" class="box-input" id="unit_label" name="unit_label">
                                        </div>
                                    </div>
                                
                                    <!-- Metadata -->
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="inputs">
                                            <label for="metadata_key" class="input-label">
                                                {{ __('Metadata Key') }}
                                            </label>
                                            <input type="text" class="box-input" id="metadata_key" name="metadata_key">
                                        </div>
                                        <div class="inputs">
                                            <label for="metadata_value" class="input-label">
                                                {{ __('Metadata Value') }}
                                            </label>
                                            <input type="text" class="box-input" id="metadata_value" name="metadata_value">
                                        </div>
                                    </div>
                                
                                    <!-- Marketing Feature List -->
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="inputs">
                                            <label for="marketing_feature" class="input-label">
                                                {{ __('Marketing Feature List') }}
                                            </label>
                                            <input type="text" class="box-input" id="marketing_feature" name="marketing_feature">
                                        </div>
                                        <button type="button" id="add-more-features" class="site-btn-sm primary-btn" style="margin-bottom:12px;">
                                            {{ __('Add Line') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Recurring or One-off -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="product_type" class="input-label d-block">
                                            {{ __('Product Type') }}
                                        </label>
                                        <select class="box-input" id="product_type" name="product_type">
                                            <option value="recurring">{{ __('Recurring') }}</option>
                                            <option value="one-off">{{ __('One-off') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="product_price" class="input-label">
                                            {{ __('Amount (required)') }}<span class="required">*</span>
                                        </label>
                                        <input type="number" class="box-input" id="product_price" name="product_price" step="0.01" required>
                                    </div>
                                </div>

                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="tax_price" class="input-label d-block">
                                            {{ __('Include tax in price') }}
                                        </label>
                                        <select class="box-input" id="tax_price" name="tax_price">
                                            <option value="Auto">{{ __('Auto') }}</option>
                                            <option value="Yes">{{ __('Yes') }}</option>
                                            <option value="No">{{ __('No') }}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Billing Period -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="billing_period" class="input-label d-block">
                                            {{ __('Billing Period') }}
                                        </label>
                                        <select class="box-input" id="billing_period" name="billing_period">
                                            <option value="monthly">{{ __('Daily') }}</option>
                                            <option value="monthly">{{ __('Weekly') }}</option>
                                            <option value="monthly">{{ __('Monthly') }}</option>
                                            <option value="yearly">{{ __('Yearly') }}</option>
                                            <option value="Every 3 months">{{ __('Every 3 months') }}</option>
                                            <option value="Every 6 months">{{ __('Every 6 months') }}</option>
                                            <option value="Custom">{{ __('Custom') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-btns mt-3">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i data-lucide="check"></i> {{ __('Add Product') }}
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
<script>
    document.getElementById('more-options-toggle').addEventListener('click', function () {
    const moreOptions = document.getElementById('more-options-section');
    if (moreOptions.style.display === 'none') {
        moreOptions.style.display = 'block';
    } else {
        moreOptions.style.display = 'none';
    }
});

document.getElementById('add-more-features').addEventListener('click', function () {
    const featureInput = document.createElement('input');
    featureInput.type = 'text';
    featureInput.className = 'box-input mt-2';
    featureInput.name = 'marketing_features[]';
    featureInput.placeholder = 'Enter another feature';
    document.getElementById('more-options-section').appendChild(featureInput);
});

</script>