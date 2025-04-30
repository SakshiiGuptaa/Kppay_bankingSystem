<div class="col-xl-12 col-lg-12 col-md-12 col-12">
    <div class="site-card">
        <div class="site-card-body transfer-top-btns">
            <a href="{{ route('user.merchant_account.index') }}" class="site-btn-sm {{ isActive('user.merchant_account.index') }}"><i data-lucide="send"></i> All Products</a>
            <a href="{{ route('user.merchant_account.features') }}" class="site-btn-sm {{ isActive('user.merchant_account.features') }}"><i data-lucide="alert-circle"></i> Features</a>
            <a href="{{ route('user.merchant_account.coupons') }}" class="site-btn-sm {{ isActive('user.merchant_account.coupons') }}"><i data-lucide="wifi"></i> Coupons</a>
            <a href="{{ route('user.merchant_account.shipping.rates') }}" class="site-btn-sm {{ isActive('user.merchant_account.shipping.rates') }}"><i data-lucide="send"></i> Shipping rates</a>
            <a href="{{ route('user.merchant_account.tax.rates') }}" class="site-btn-sm {{ isActive('user.merchant_account.tax.rates') }}"><i data-lucide="send"></i> Tax rates</a>
            <a href="{{ route('user.merchant_account.pricing.table') }}" class="site-btn-sm {{ isActive('user.merchant_account.pricing.table') }}"><i data-lucide="user-check"></i> Pricing tables</a>
        </div>
    </div>
</div>
