@extends('frontend::layouts.user')

@section('title')
    {{ __('Merchant Account') }}
@endsection

@section('content')
    <div class="row">
       <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <div class="site-card">
                <div class="site-card-header">
                    <div class="title">{{ __('Merchant Account') }}</div>
                    <div style="display:flex; gap:1px;">
                        
                    <div class="card-header-links">
                        <a href="#" class="card-header-link" data-bs-toggle="modal" data-bs-target="#addBox"><i
                                data-lucide="plus-circle"></i>{{ __('New Merchant') }}</a>
                    </div>
                    <div class="card-header-links">
                        <a href="#" class="card-header-link" data-bs-toggle="modal" data-bs-target="#paymentBox"><i
                                data-lucide="plus-circle"></i>{{ __('Payment') }}</a>
                    </div>
                    <div class="card-header-links">
                        
                        <a href="merchant-account/product" class="card-header-link">
                            <i data-lucide="plus-circle"></i>{{ __('View More Options') }}
                        </a>
                    </div>    
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <div>
                        @if ($merchantAccounts->count() > 0)
                            <!-- Display Merchant Table -->
                        <div class="site-custom-table">
                            <div class="contents">
                            <!-- Table Header -->
                            <div class="site-table-list site-table-head">
                                <div class="site-table-col">{{ __('Id') }}</div>
                                <div class="site-table-col">{{ __('Business Name') }}</div>
                                <div class="site-table-col">{{ __('Currency') }}</div>
                                <div class="site-table-col">{{ __('Merchant Type') }}</div>
                                <div class="site-table-col">{{ __('Business Logo') }}</div>
                                <div class="site-table-col">{{ __('QR Code') }}</div>
                                <div class="site-table-col">{{ __('Payment Link') }}</div>
                                <div class="site-table-col">{{ __('Actions') }}</div>
                            </div>
                    
                            <!-- Table Content -->
                        @foreach($merchantAccounts as $merchant)
                        <div class="site-table-list">
                            <!-- Id -->
                                    <div class="site-table-col">
                                        <div class="trx fw-bold">{{ $merchant->id }}</div>
                                    </div>
                    
                                    <!-- Business Name -->
                                    <div class="site-table-col">
                                        <div class="description">
                                            <div class="content">
                                                <div class="title">{{ $merchant->business_name }}</div>
                                                <div class="trx fw-bold">{{ $merchant->site_url }}</div>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <!-- Currency -->
                                    <div class="site-table-col">
                                        <div class="trx fw-bold">{{ $merchant->currency }}</div>
                                    </div>
                    
                                    <!-- Merchant Type -->
                                    <div class="site-table-col">
                                        <div class="trx fw-bold">{{ $merchant->merchant_type }}</div>
                                    </div>
                    
                                    <!-- Business Logo -->
                                    <div class="site-table-col">
                                        <div class="description">
                                            @if($merchant->business_logo)
                                                <img src="{{ asset($merchant->business_logo) }}" alt="{{ $merchant->business_name }}" width="auto" height="30">
                                            @else
                                                <span>{{ __('No Logo') }}</span>
                                            @endif
                                        </div>
                                    </div>
                    
                                    <!-- QR Code -->
                                    <div class="site-table-col">
                                        @php
                                            $qrCodeUrl = url("/merchant-account/" . $merchant->id);
                                        @endphp
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $qrCodeUrl }}" alt="{{ __('QR Code') }}" width="50" height="50">
                                    </div>
                                    
                                    <!-- Payment Link -->
                                     <div class="site-table-col">
                                        <div class="action">
                                            
                                            <a href="#" class="icon-btn me-2" style="background: #0dc5f4;" data-bs-toggle="modal" data-bs-target="#paybtnModal">
                                             {{ __('Pay Btn') }}
                                            </a>
                                        </div>    
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="site-table-col">
                                        <div class="action">
                                            <!-- View Details -->
                                            <a href="" class="icon-btn me-2">
                                                <i data-lucide="eye"></i> {{ __('View') }}
                                            </a>
                                            
                                            <!-- Edit -->
                                            <a href="" class="icon-btn me-2">
                                                <i data-lucide="edit"></i> {{ __('Edit') }}
                                            </a>
                    
                                            <!-- Delete -->
                                            <form action="" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="circle-btn red-btn">
                                                    <i data-lucide="trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                        
                                <!-- Pagination -->
                              
                        
                                <!-- No Data Found -->
                                @if($merchantAccounts->isEmpty())
                                    <div class="no-data-found">{{ __('No Data Found!') }}</div>
                                @endif
                            </div>
                        </div>

                        @else
                        <!-- No Products Content -->
                        <div class="no-products text-center">
                            <div class="icon mb-2">
                                <i class="box-icon"></i> <!-- Add an appropriate box icon -->
                            </div>
                            <h3 class="title">{{ __('Add your first Merchant Account') }}</h3>
                            <p class="description">
                                {{ __('List of all the merchant accounts in one place') }}
                            </p>
                            <a href="#" class="learn-more">{{ __('Learn more') }} →</a>
                            <div class="mt-3">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBox">{{ __('New Merchant') }}</button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>


        <!-- Modal for Add Product -->
        @include('frontend::merchant_account.include.__add_merchant')
        <!-- Modal for Edit Product -->
        
        <!-- Payment Button -->
    <div class="modal fade" id="paybtnModal" tabindex="-1" aria-labelledby="paybtnModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
                <div class="popup-body-text">
                    <div class="title">{{ __('Payment Link') }}</div>
                    <form action="{{ route('user.merchant_account.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="step-details-form">
                          
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-btns mt-3">
                          
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
    
    
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="edit_product_id" name="product_id">
              <div class="mb-3">
                <label for="edit_product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="edit_product_name" name="product_name" required>
              </div>
              <div class="mb-3">
                <label for="edit_product_description" class="form-label">Product Description</label>
                <textarea class="form-control" id="edit_product_description" name="product_description" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="edit_product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="edit_product_image" name="product_image">
              </div>
              <div class="mb-3">
                <label for="edit_product_tax_code" class="form-label">Product Tax Code</label>
                <input type="text" class="form-control" id="edit_product_tax_code" name="product_tax_code">
              </div>
              <div class="mb-3">
                <label for="edit_product_type" class="form-label">Product Type</label>
                <input type="text" class="form-control" id="edit_product_type" name="product_type">
              </div>
              <div class="mb-3">
                <label for="edit_product_price" class="form-label">Product Price</label>
                <input type="number" class="form-control" id="edit_product_price" name="product_price" step="0.01" required>
              </div>
              <div class="mb-3">
                <label for="edit_tax_price" class="form-label">Tax Price</label>
                <input type="text" class="form-control" id="edit_tax_price" name="tax_price">
              </div>
              <div class="mb-3">
                <label for="edit_billing_period" class="form-label">Billing Period</label>
                <input type="text" class="form-control" id="edit_billing_period" name="billing_period">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script>
    document.querySelectorAll('.edit-product-btn').forEach(button => {
      button.addEventListener('click', function () {
        // Get product data from button attributes
        const productId = button.getAttribute('data-id');
        const productName = button.getAttribute('data-name');
        const productDescription = button.getAttribute('data-description');
        const productPrice = button.getAttribute('data-price');
        const productTaxCode = button.getAttribute('data-tax-code');
        const productType = button.getAttribute('data-type');
        const taxPrice = button.getAttribute('data-tax-price');
        const productImage = button.getAttribute('data-image');
        const billingPeriod = button.getAttribute('data-billing-period');
    
        // Populate modal fields
        document.getElementById('edit_product_id').value = productId;
        document.getElementById('edit_product_name').value = productName;
        document.getElementById('edit_product_description').value = productDescription;
        document.getElementById('edit_product_price').value = productPrice;
        document.getElementById('edit_product_tax_code').value = productTaxCode;
        document.getElementById('edit_product_type').value = productType;
        document.getElementById('edit_tax_price').value = taxPrice;
        document.getElementById('edit_billing_period').value = billingPeriod;
    
        // Optionally preview the product image
        const imagePreview = document.getElementById('imagePreview');
        if (imagePreview) {
          imagePreview.src = productImage; // Ensure you have an <img> tag with id="imagePreview" in the modal
        }
    
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
        modal.show();
      });
    });
    
    
    </script>

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
        margin-left: 0.1rem;
        padding: 0.5rem 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: none;
        cursor: pointer;
    }
    
    .filter-actions .action-btn:hover{
        background-color:blue;
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
