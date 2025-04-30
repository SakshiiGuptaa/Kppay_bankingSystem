<div class="modal fade" id="addBox" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
                <div class="popup-body-text">
                    <div class="title">{{ __('Create Coupon') }}</div>
                    <form action="{{ route('user.merchant_account.coupon.store') }}" method="POST">
                        @csrf
                        <div class="step-details-form">
                            <div class="row">
                                <!-- Existing Fields -->
                                <div class="inputs">
                                    <label for="name" class="input-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name" class="box-input" placeholder="First purchase discount" required>
                                    <small style="font-size:14px;">This will appear on customers' receipts and invoices.</small>
                                </div>
                        
                                <div class="inputs">
                                    <label for="id" class="input-label">{{ __('ID') }}</label>
                                    <input type="text" name="id" id="id" class="box-input" placeholder="Optional">
                                    <small style="font-size:14px;">This will identify this coupon in the API. Leave blank to generate an ID automatically.</small>
                                </div>
                        
                                <div class="inputs">
                                    <label class="input-label">{{ __('Type') }}</label>
                                    <div>
                                        <input type="radio" name="type" id="percentage" value="percentage" checked>
                                        <label for="percentage">{{ __('Percentage discount') }}</label></br>
                                        <input type="radio" name="type" id="fixed" value="fixed">
                                        <label for="fixed">{{ __('Fixed amount discount') }}</label>
                                    </div>
                                </div>
                        
                                <div class="inputs">
                                    <label for="percentage_off" class="input-label">{{ __('Percentage off') }}</label>
                                    <input type="number" name="percentage_off" id="percentage_off" class="box-input" placeholder="%" required>
                                </div>
                                
                                <div class="inputs">
                                    <label class="input-label">
                                        <input type="checkbox" name="apply_specific_products" id="apply_specific_products">
                                        {{ __('Apply to specific products') }}
                                    </label>
                                </div>
                                
                                <div class="inputs">
                                    <label for="duration" class="input-label">{{ __('Duration') }}</label>
                                    <select name="duration" id="duration" class="box-input">
                                        <option value="once">Once</option>
                                        <option value="forever" selected>Forever</option>
                                        <option value="repeating" selected>Multiple months</option>
                                    </select>
                                    <small style="font-size:14px;">Determines how long this coupon will apply once redeemed.</small>
                                </div>
                        
                                <label for="limit" class="inputs input-label">{{ __('Redemption limits') }}</label>
                                <div class="inputs">
                                    <label class="input-label">
                                        <input type="checkbox" name="limit_date_range" id="limit_date_range">
                                        {{ __('Limit the date range when customers can redeem this coupon') }}
                                    </label>
                                </div>
                        
                                <div class="inputs">
                                    <label class="input-label">
                                        <input type="checkbox" name="limit_redemption" id="limit_redemption">
                                        {{ __('Limit the total number of times this coupon can be redeemed') }}
                                    </label>
                                </div>
                        
                                <!-- New "Codes" Section -->
                                <div class="inputs">
                                    <label class="input-label">{{ __('Codes') }}</label></br>
                                    <label class="switch">
                                        <input type="checkbox" name="use_customer_codes" id="use_customer_codes">
                                        <span class="slider round"></span>
                                        {{ __('Use customer-facing coupon codes') }}
                                    </label>
                                </div>
                        
                                <div id="codes-section" style="display:none;">
                                    <div class="inputs">
                                        <label for="coupon_code" class="input-label">{{ __('Code') }}</label>
                                        <input type="text" name="coupon_code" id="coupon_code" class="box-input" placeholder="Enter Coupon Code">
                                    </div>
                        
                                    <div class="inputs">
                                        <label class="input-label">
                                            <input type="checkbox" name="first_time_only" id="first_time_only">
                                            {{ __('Eligible for first-time orders only') }}
                                        </label>
                                    </div>
                        
                                    <div class="inputs">
                                        <label class="input-label">
                                            <input type="checkbox" name="specific_customer" id="specific_customer">
                                            {{ __('Limit to a specific customer') }}
                                        </label>
                                    </div>
                        
                                    <div class="inputs">
                                        <label class="input-label">
                                            <input type="checkbox" name="redemption_limit" id="redemption_limit">
                                            {{ __('Limit the number of times this code can be redeemed') }}
                                        </label>
                                    </div>
                        
                                    <div class="inputs">
                                        <label class="input-label">
                                            <input type="checkbox" name="add_expiry" id="add_expiry">
                                            {{ __('Add an expiry date') }}
                                        </label>
                                    </div>
                        
                                    <div class="inputs">
                                        <label class="input-label">
                                            <input type="checkbox" name="min_order_value" id="min_order_value">
                                            {{ __('Require minimum order value') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="action-btns mt-3">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i data-lucide="check"></i> {{ __('Create Coupon') }}
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
    // Toggle visibility of Codes section based on checkbox
    document.getElementById('use_customer_codes').addEventListener('change', function () {
        const codesSection = document.getElementById('codes-section');
        if (this.checked) {
            codesSection.style.display = 'block';
        } else {
            codesSection.style.display = 'none';
        }
    });
</script>