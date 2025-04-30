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
                                data-lucide="plus-circle"></i>{{ __('Create Product') }}</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <div class="site-card">
                <!-- Top Filters -->
                <div class="filters d-flex align-items-center justify-content-between mb-3 site-card-header" style="padding:5px;">
                    <div class="status-filters">
                        <button class="filter-btn active">All <span>0</span></button>
                        <button class="filter-btn">Active <span>0</span></button>
                        <button class="filter-btn">Archived <span>0</span></button>
                    </div>
                    <div class="card-header-links" style="display:flex; height:40px;">
                        <button class="card-header-link" style="background-color:#ce0202;" data-bs-toggle="modal" data-bs-target="#exportPricesModal">Export Prices</button>
                        <button class="card-header-link"  style="background-color:#ce0202;" data-bs-toggle="modal" data-bs-target="#exportProductsModal">Export Products</button>
                        <button class="card-header-link"  style="background-color:#ce0202;">Edit Columns</button>
                    </div>

                </div>
                
                <!-- Modal for Export Prices -->
                <div class="modal fade" id="exportPricesModal" tabindex="-1" aria-labelledby="exportPricesModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <form id="uploadPricesForm" method="POST" enctype="multipart/form-data">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exportPricesModalLabel">Export Prices</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p>Are you sure you want to export prices?</p>
                          <div class="mb-3">
                            <label for="uploadPricesFile" class="form-label">Upload File for Export</label>
                            <input type="file" class="form-control" id="uploadPricesFile" name="prices_file" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-primary">Upload & Export</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                
                <!-- Modal for Export Products -->
                <div class="modal fade" id="exportProductsModal" tabindex="-1" aria-labelledby="exportProductsModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <form id="uploadProductsForm" method="POST" enctype="multipart/form-data">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exportProductsModalLabel">Export Products</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p>Are you sure you want to export products?</p>
                          <div class="mb-3">
                            <label for="uploadProductsFile" class="form-label">Upload File for Export</label>
                            <input type="file" class="form-control" id="uploadProductsFile" name="products_file" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-primary">Upload & Export</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>



               <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                        <div>
                            @if ($products->count() > 0)
                                <!-- Display Products Table -->
                                <div class="products-table">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <!--<th>{{ __('Image') }}</th>-->
                                                <th>{{ __('No.') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                 <th>{{ __('QR Code') }}</th> <!-- New Column -->
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $index=0; ?>
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>{{ $product->product_description }}</td>
                                                    <td>{{ $product->product_price }}</td>
                                                    <td>
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode(url('/' . str_replace(' ', '-', $product->product_name). '/'. $product->id)) }}" alt="QR Code">
                                                    </td>
                                                    
                                                    <td>
                                                        <!-- Action Buttons -->
                                                   <a href="#" 
                                                       class="btn btn-sm btn-primary edit-product-btn" 
                                                       data-id="{{ $product->id }}"
                                                       data-name="{{ $product->product_name }}"
                                                       data-description="{{ $product->product_description }}"
                                                       data-price="{{ $product->product_price }}"
                                                       data-tax-code="{{ $product->product_tax_code }}"
                                                       data-type="{{ $product->product_type }}"
                                                       data-tax-price="{{ $product->tax_price }}"
                                                       data-image="{{ $product->product_image }}"
                                                       data-billing-period="{{ $product->billing_period }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#editProductModal"><i class="fas fa-edit"></i> <!-- Font Awesome Edit Icon --></a>


                                                        <form action="#" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash-alt"></i> <!-- Font Awesome Delete Icon --></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                            <!-- No Products Content -->
                            <div class="no-products text-center">
                                <div class="icon mb-2">
                                    <i class="box-icon"></i> <!-- Add an appropriate box icon -->
                                </div>
                                <h3 class="title">{{ __('Add your first product') }}</h3>
                                <p class="description">
                                    {{ __('Products are what you sell to customers. They can be anything from physical goods to digital services or subscription plans.') }}
                                </p>
                                <a href="#" class="learn-more">{{ __('Learn more') }} →</a>
                                <div class="mt-3">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBox">{{ __('Add a product') }}</button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

            </div>
    </div>              

        <!-- Modal for Add Product -->
        @include('frontend::merchant_account.include.__add_product')
        <!-- Modal for Edit Product -->
        
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
