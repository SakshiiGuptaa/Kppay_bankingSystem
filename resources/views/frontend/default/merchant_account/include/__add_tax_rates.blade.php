<div class="modal fade" id="addBox" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
                <div class="popup-body-text">
                    <div class="title">{{ __('Add Tax Rate') }}</div>
                    <form action="{{ route('user.merchant_account.taxrate.store') }}" method="POST">
                        @csrf

                        <!-- Tax Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type') }}</label>
                            <select class="form-select box-input" id="type" name="type" aria-describedby="typeHelp">
                                <option value="Sales tax">{{ __('Sales tax') }}</option>
                                <option value="VAT">{{ __('VAT') }}</option>
                            </select>
                            <small id="typeHelp" class="form-text text-muted">{{ __('Select the type of tax.') }}</small>
                        </div>

                        <!-- Region -->
                        <div class="mb-3">
                            <label for="region" class="form-label">{{ __('Region') }}</label>
                            <select class="form-select box-input" id="region" name="region" aria-describedby="regionHelp">
                                <option value="">{{ __('Select region...') }}</option>
                                <option value="US">{{ __('United States') }}</option>
                                <option value="EU">{{ __('European Union') }}</option>
                            </select>
                            <small id="regionHelp" class="form-text text-muted">{{ __('The region where the tax applies.') }}</small>
                        </div>

                        <!-- Rate -->
                        <div class="mb-3">
                            <label for="rate" class="form-label">{{ __('Rate') }} <span class="text-danger">{{ __('Required') }}</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control box-input" id="rate" name="rate" step="0.01" placeholder="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <!-- Include Tax in Price -->
                        <div class="mb-3">
                            <label for="include_tax" class="form-label">{{ __('Include tax in price') }}</label>
                            <select class="form-select box-input" id="include_tax" name="include_tax">
                                <option value="No">{{ __('No (exclusive)') }}</option>
                                <option value="Yes">{{ __('Yes (inclusive)') }}</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <input type="text" class="form-control box-input" id="description" name="description" placeholder="{{ __('Add a description...') }}">
                        </div>

                        <!-- Information Note -->
                        <div class="info-box mt-3 p-3 bg-light rounded border">
                            <i class="info-icon me-2" data-lucide="info"></i>
                            <span>{{ __('Save time and collect tax automatically with KPPAY Tax.') }}
                                <a href="#" class="text-primary">{{ __('Start now') }}</a>
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Add tax rate') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
