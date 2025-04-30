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
                                data-lucide="plus-circle"></i>{{ __('Create pricing table') }}</a>
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
                
                  <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <div>
                        @if ($pricingTable->count() > 0)
                            <!-- Display Pricing Table -->
                            <div class="products-table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No.') }}</th>
                                            <th>{{ __('Product Name') }}</th>
                                            <th>{{ __('Default View') }}</th>
                                            <th>{{ __('Language') }}</th>
                                            <th>{{ __('Background Color') }}</th>
                                            <th>{{ __('Button Color') }}</th>
                                            <th>{{ __('Font') }}</th>
                                            <th>{{ __('Button Shape') }}</th>
                                            <th>{{ __('Highlight Product') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $index = 0; ?>
                                        @foreach ($pricingTable as $item)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ ucfirst($item->default_view) }}</td>
                                                <td>{{ strtoupper($item->language) }}</td>
                                                <td>
                                                    <span style="background-color: {{ $item->background_color }}; padding: 5px 10px; border-radius: 5px; color: #000;">
                                                        {{ $item->background_color }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span style="background-color: {{ $item->button_color }}; padding: 5px 10px; border-radius: 5px; color: #000;">
                                                        {{ $item->button_color }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->font }}</td>
                                                <td>{{ ucfirst($item->button_shape) }}</td>
                                                <td>
                                                    @if ($item->highlight_product)
                                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('No') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Action Buttons -->
                                                    <a href="#" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> <!-- Font Awesome Edit Icon -->
                                                    </a>
                                                    <form action="#" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash-alt"></i> <!-- Font Awesome Delete Icon -->
                                                        </button>
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
                                <h3 class="title">{{ __('Create a pricing table') }}</h3>
                                <p class="description">
                                    {{ __('Create a branded, responsive pricing table to embed on your website.') }}
                                </p>
                                <a href="#" class="learn-more">{{ __('View docs') }} →</a>
                                <div class="mt-3">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBox">{{ __('Create Pricing Table') }}</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal for Add Product -->
        @include('frontend::merchant_account.include.__add_pricing_table')
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
