@extends('frontend::layouts.user')

@section('title')
    {{ __('Merchant Account') }}
@endsection

@section('content')
    <div class="row">
        @include('frontend::merchant_account.include.__header')

       <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <div class="site-card">
                <div class="site-card-header">
                    <div class="title">{{ __('Merchant Account') }}</div>
                    <div class="card-header-links">
                        <a href="#" class="card-header-link" data-bs-toggle="modal" data-bs-target="#addBox"><i
                                data-lucide="plus-circle"></i>{{ __('Create shipping rate') }}</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="site-card">
                <!-- Top Filters -->
                <div class="filters d-flex align-items-center justify-content-between mb-3">
                    <div class="status-filters">
                        <button class="filter-btn active">All <span>0</span></button>
                        <button class="filter-btn">Active <span>0</span></button>
                        <button class="filter-btn">Archived <span>0</span></button>
                    </div>
                    <div class="filter-actions">
                        <button class="action-btn">Export Prices</button>
                        <button class="action-btn">Export Products</button>
                        <button class="action-btn">Edit Columns</button>
                    </div>
                </div>

                <div>
                    @if ($shippingRates->count() > 0)
                        <!-- Display Shipping Rates Table -->
                        <div class="shipping-rates-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('No.') }}</th>
                                        <th>{{ __('Tax Price') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Currency') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Min Delivery Days') }}</th>
                                        <th>{{ __('Max Delivery Days') }}</th>
                                        <th>{{ __('Tax Code') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $index = 0;?>
                                    @foreach ($shippingRates as $rate)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>
                                                @if ($rate->tax_price === 'Auto')
                                                    <span class="badge bg-success">{{ __('Auto') }}</span>
                                                @else
                                                    <span class="badge bg-primary">{{ __('Set Manually') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($rate->amount, 2) }}</td> <!-- Format amount to 2 decimal places -->
                                            <td>{{ strtoupper($rate->currency) }}</td> <!-- Ensure currency is uppercase -->
                                            <td>{{ $rate->description ?? __('N/A') }}</td> <!-- Display 'N/A' if description is null -->
                                            <td>{{ $rate->min_days }}</td>
                                            <td>{{ $rate->max_days }}</td>
                                            <td>
                                                @if ($rate->tax_code === 'shipping')
                                                    <span class="badge bg-info">{{ __('Shipping') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Non-Taxable') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Action Buttons -->
                                                <a href="#" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                                <form action="#" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                    <!-- No Content -->
                     <div class="no-products text-center">
                        <div class="icon mb-2">
                            <i class="box-icon"></i> <!-- Add an appropriate box icon -->
                        </div>
                        <h3 class="title">{{ __('Add your first shipping rate') }}</h3>
                        <p class="description">
                            {{ __('Specify shipping rates and use it on your customers’ receipts, invoices, and Checkout Sessions.') }}
                        </p>
                        <a href="#" class="learn-more">{{ __('Learn more') }} →</a>
                        <div class="mt-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBox">{{ __('Create shipping rate') }}</button>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Modal for Add Product -->
        @include('frontend::merchant_account.include.__add_shipping_rates')
    </div>
@endsection

@section('script')
    <!-- Add custom scripts if needed -->
@endsection

<style>
    .filters {
    gap: 1rem;
}

.filter-btn {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 0.5rem 1rem;
    background: none;
    cursor: pointer;
}

.filter-btn.active {
    background-color: #ecefff;
    color: #3c50ff;
    font-weight: bold;
}

.filter-actions .action-btn {
    margin-left: 0.5rem;
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: none;
    cursor: pointer;
}

.no-products {
    padding: 2rem;
    border: 1px dashed #ccc;
    border-radius: 8px;
}

.no-products .icon {
    font-size: 3rem;
    color: #6c757d;
}

.no-products .title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.no-products .description {
    color: #6c757d;
    margin-bottom: 1rem;
}

.no-products .learn-more {
    color: #3c50ff;
    font-weight: bold;
    text-decoration: none;
}

.btn-primary {
    background-color: #3c50ff;
    border: none;
    padding: 0.5rem 1.5rem;
    color: #fff;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
}

</style>
