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
                                data-lucide="plus-circle"></i>{{ __('Create Feature') }}</a>
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
                @if ($features->count() > 0)
                    <!-- Display Features Table -->
                    <div class="features-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Feature Name</th>
                                    <th>Lookup Key</th>
                                    <th>Metadata</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index=0; ?>
                                @foreach ($features as $feature)
                                    <tr>
                                        <td>{{ ++$index;}}</td>
                                        <td>{{ $feature->feature_name }}</td>
                                        <td>{{ $feature->lookup_key }}</td>
                                        <td>
                                            @foreach ($feature->metadata as $key => $value)
                                                <p>{{ $key }}: {{ $value }}</p>
                                            @endforeach
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
                <!-- No Feature Content -->
                <div class="col-12">
                    <div class="site-card">
                      
                        <!-- No Feature Content -->
                        <div class="no-products text-center">
                            <div class="icon mb-2">
                                <i class="box-icon"></i> <!-- Add an appropriate box icon -->
                            </div>
                            <h3 class="title">{{ __('Start by adding a feature') }}</h3>
                            <p class="description">
                                {{ __('Features are monetisable capabilities that can be linked to products, granting purchasing customers an entitlement to the feature through Stripe.') }}
                            </p>
                            <a href="#" class="learn-more">{{ __('Learn more') }} →</a>
                            <div class="mt-3">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBox">{{ __('Add a Feature') }}</button>
                            </div>
                        </div>
                    
                    </div>
                </div>
                @endif
            </div>
        </div>

            </div>
        </div>

        <!-- Modal for Add Product -->
        @include('frontend::merchant_account.include.__add_feature')
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
