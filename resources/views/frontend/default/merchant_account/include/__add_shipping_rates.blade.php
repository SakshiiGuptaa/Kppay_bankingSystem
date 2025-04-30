<div class="modal fade" id="addBox" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
                <div class="popup-body-text">
                    <div class="title">{{ __('Add Shipping Rate') }}</div>
                    <form action="{{ route('user.merchant_account.shipping.store') }}" method="POST">
                        @csrf
                        <div class="step-details-form">
                            <div class="row">
                                <!-- Amount Section -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="tax_price" class="input-label d-block">
                                            {{ __('Include tax in price') }}
                                        </label>
                                        <select class="box-input" id="tax_price" name="tax_price">
                                            <option value="Auto">{{ __('Auto') }}</option>
                                            <option value="Set_manually">{{ __('Set manually') }}</option>
                                        </select>
                                    </div>
                                    <div class="inputs">
                                        <label for="amount" class="input-label d-block">
                                            {{ __('Amount') }}
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" class="box-input me-2" id="amount" name="amount" step="0.01">
                                            <select class="box-input" id="currency" name="currency">
                                                <option value="EUR">EUR</option>
                                                <option value="USD">USD</option>
                                            </select>
                                        </div>
                                        <span class="form-text">{{ __('This price will include tax.') }}</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="description" class="input-label d-block">
                                            {{ __('Description') }}
                                        </label>
                                        <input type="text" class="box-input" id="description" name="description">
                                    </div>
                                </div>

                                <!-- Estimated Shipping Time -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label class="input-label d-block">{{ __('Estimated Shipping Time') }}</label>
                                        <div class="d-flex align-items-center">
                                            <div>Between<input type="number" class="box-input me-2" name="min_days" placeholder="{{ __('min') }}">
                                            <span class="me-2">{{ __('Business Days') }}</span></div><div>
                                            and<input type="number" class="box-input me-2" name="max_days" placeholder="{{ __('max') }}">
                                            <span>{{ __('Business Days') }}</span></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shipping Tax Code -->
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="inputs">
                                        <label for="tax_code" class="input-label d-block">
                                            {{ __('Shipping Tax Code') }}
                                        </label>
                                        <select class="box-input" id="tax_code" name="tax_code">
                                            <option value="shipping">{{ __('Shipping') }}</option>
                                            <option value="non-taxable">{{ __('Non-taxable') }}</option>
                                        </select>
                                        <span class="form-text">{{ __('A shipping charge for the delivery of physical goods in conjunction with the sale of these goods. This tax category is not appropriate for stand alone transportation charges that are not associated with the sale of the goods being delivered.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-btns mt-3">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i data-lucide="check"></i> {{ __('Save') }}
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
