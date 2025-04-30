<?php

namespace App\Http\Controllers\Frontend;

use Validator;
use App\Models\MerchantAccountPricingTables;
use App\Models\MerchantAccountTaxRates;
use App\Models\MerchatAccountShippingRates;
use App\Models\MerchantAccountCoupon;
use App\Models\MerchantAccountProduct;
use App\Models\MerchantAccountFeature;
use App\Models\MerchantAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MerchantAccountController extends Controller
{
    //merchant list page
    public function index()
    {
        // Fetch all products for the logged-in user
        $merchantAccounts = MerchantAccount::where('user_id', Auth::id())->get();
    
        return view('frontend::merchant_account.index', compact('merchantAccounts'));
    }

    //merchant store
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'site_url' => 'required|url|max:255',
            'currency' => 'required|string|max:10',
            'merchant_type' => 'required|string|max:50',
            'message' => 'nullable|string',
            'business_logo' => 'required|image|mimes:jpeg,png,webp|max:2048', // Max file size 2MB
        ]);

        // Handle the file upload for the business logo
        $logoPath = null;
        if ($request->hasFile('business_logo')) {
            $logoPath = $request->file('business_logo')->store('front/business_logos', 'public');
        }

        // Get user data
        $user = auth()->user();
        
        // Save the merchant account to the database
        MerchantAccount::create([
            'user_id' => $user->id,
            'business_name' => $validated['business_name'],
            'site_url' => $validated['site_url'],
            'currency' => $validated['currency'],
            'merchant_type' => $validated['merchant_type'],
            'message' => $validated['message'] ?? null,
            'business_logo' => $logoPath,
        ]);
        notify()->success('Merchant account created successfully.');
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Merchant account created successfully.');
    }    

    public function products()
    {
        // Fetch all products for the logged-in user
        $products = MerchantAccountProduct::where('user_id', Auth::id())->get();
    
        return view('frontend::merchant_account.products', compact('products'));
    }
    
        public function productstore(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'product_tax_code' => 'required|string',
            'product_type' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'tax_price' => 'required|string',
            'billing_period' => 'required|string',
        ]);

        // Handle file upload
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('product_images', 'public');
            $validatedData['product_image'] = $imagePath;
        }
        
        // Add user_id to the data
        $validatedData['user_id'] = Auth::id();
        
        // Save the data to the database
        MerchantAccountProduct::create($validatedData);

        return redirect()->back()->with('success', 'Product added successfully!');
    }
        
    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'product_price' => 'required|numeric|min:0',
            'product_image' => 'nullable|file|image|mimes:jpeg,png,webp|max:2048',
        ]);
    
        $product = Product::find($request->product_id);
        $product->product_name = $validated['product_name'];
        $product->product_description = $validated['product_description'];
        $product->product_price = $validated['product_price'];
    
        if ($request->hasFile('product_image')) {
            $product->product_image = $request->file('product_image')->store('products', 'public');
        }
    
        $product->save();
    
        return redirect()->back()->with('success', 'Product updated successfully!');
    }
    
    public function features()
    {
        // Fetch all products for the logged-in user
        $features = MerchantAccountFeature::where('user_id', Auth::id())->get();
    
        return view('frontend::merchant_account.features', compact('features'));
    }
    
    public function featureStore(Request $request)
    {
    // Validate request data
    $request->validate([
        'feature_name' => 'required|string|max:255',
        'lookup_key' => 'required|string|max:255|unique:merchant_account_features,lookup_key',
        'metadata_keys' => 'array',
        'metadata_values' => 'array',
    ]);

    // Prepare metadata as a key-value pair
    $metadata = [];
    if ($request->has('metadata_keys') && $request->has('metadata_values')) {
        foreach ($request->metadata_keys as $index => $key) {
            $metadata[$key] = $request->metadata_values[$index];
        }
    }

    // Store data in the database
    MerchantAccountFeature::create([
        'user_id' => Auth::id(),
        'feature_name' => $request->feature_name,
        'lookup_key' => $request->lookup_key,
        'metadata' => $metadata,
    ]);

    return redirect()->back()->with('success', 'Feature created successfully.');
    }
    
    public function coupons()
    {
        // Fetch all products for the logged-in user
        $coupon = MerchantAccountCoupon::where('user_id', Auth::id())->get();
    
        return view('frontend::merchant_account.coupons', compact('coupon'));
    }

    public function couponStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'percentage_off' => 'nullable|numeric',
            'apply_specific_products' => 'nullable|string',
            'duration' => 'required|in:once,forever,repeating',
            'limit_date_range' => 'nullable|string',
            'limit_redemption' => 'nullable|string',
            'use_customer_codes' => 'nullable|string',
            'coupon_code' => 'nullable|string|max:255',
            'first_time_only' => 'nullable|string',
            'specific_customer' => 'nullable|string',
            'redemption_limit' => 'nullable|string',
            'add_expiry' => 'nullable|string',
            'min_order_value' => 'nullable|string',
        ]);
    
        // Convert checkbox values from 'on' to 1 or set to 0 if not checked
        $validated['apply_specific_products'] = $request->has('apply_specific_products') ? 1 : 0;
        $validated['limit_date_range'] = $request->has('limit_date_range') ? 1 : 0;
        $validated['limit_redemption'] = $request->has('limit_redemption') ? 1 : 0;
        $validated['use_customer_codes'] = $request->has('use_customer_codes') ? 1 : 0;
        $validated['first_time_only'] = $request->has('first_time_only') ? 1 : 0;
        $validated['specific_customer'] = $request->has('specific_customer') ? 1 : 0;
        $validated['redemption_limit'] = $request->has('redemption_limit') ? 1 : 0;
        $validated['add_expiry'] = $request->has('add_expiry') ? 1 : 0;
        $validated['min_order_value'] = $request->has('min_order_value') ? 1 : 0;
    
        $coupon = new MerchantAccountCoupon($validated);
        $coupon->user_id = auth()->user()->id; // Assign authenticated user ID
        $coupon->save();
    
        return redirect()->back()->with('success', 'Coupon created successfully!');
    }
    
    public function shippingRates()
    {
        
        $shippingRates = MerchatAccountShippingRates::where('user_id', Auth::id())->get();
        return view('frontend::merchant_account.shipping_rates', compact('shippingRates'));
    }
    
    
    public function shippingStore(Request $request)
    {
        //   dd($request->all()); // Inspect the incoming request data
    $validated = $request->validate([
        'tax_price' => 'required|in:Auto,Set_manually',
        'amount' => 'required|numeric|min:0',
        'currency' => 'required|string|max:3',
        'description' => 'nullable|string|max:255',
        'min_days' => 'required|integer|min:0',
        'max_days' => 'required|integer|gte:min_days',
        'tax_code' => 'required|in:shipping,non-taxable',
    ]);

    // Add user_id to the data
    $validated['user_id'] = auth()->id();

    // Save data
    $shippingRate = MerchatAccountShippingRates::create($validated);

    return redirect()->back()->with('success', 'Shipping rate added successfully!');
    }

    
    public function taxRates()
    {
        // Fetch all tax rates for the logged-in user
        $taxRates = MerchantAccountTaxRates::where('user_id', Auth::id())->get();
    
        return view('frontend::merchant_account.tax_rates', compact('taxRates'));
    }
    
    public function taxStore(Request $request)
    {
        // Validate the form input
        $request->validate([
            'type' => 'required|in:Sales tax,VAT',
            'region' => 'nullable|string|max:10',
            'rate' => 'required|numeric|min:0|max:100',
            'include_tax' => 'required|in:Yes,No',
            'description' => 'nullable|string|max:255',
        ]);

        // Save the data into the database
        MerchantAccountTaxRates::create([
            'user_id' => auth()->id(), // Assuming the user is logged in
            'type' => $request->type,
            'region' => $request->region,
            'rate' => $request->rate,
            'include_tax' => $request->include_tax,
            'description' => $request->description,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', __('Tax rate added successfully.'));
    }
    
    public function pricingTable()
    {
        // Fetch all pricing tables for the logged-in user
        $pricingTable = MerchantAccountPricingTables::where('user_id', Auth::id())->get();
    
        return view('frontend::merchant_account.pricing_table', compact('pricingTable'));
    }
    
    public function pricingTableStore(Request $request)
    {
        // Validate the input data
        $request->validate([
            'product_name' => 'required|string|max:255',
            'default_view' => 'required|in:grid,list',
            'language' => 'required|string|max:10',
            'background_color' => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
            'button_color' => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
            'font' => 'required|string|max:50',
            'button_shape' => 'required|in:rounded,square',
        ]);
    
        // Normalize the highlight_product value
        $highlightProduct = $request->highlight_product === 'on' ? 1 : 0;
    
        // Save the data into the database
        MerchantAccountPricingTables::create([
            'user_id' => auth()->id(), // Ensure the user is logged in
            'product_name' => $request->product_name,
            'default_view' => $request->default_view,
            'language' => $request->language,
            'background_color' => $request->background_color,
            'button_color' => $request->button_color,
            'font' => $request->font,
            'button_shape' => $request->button_shape,
            'highlight_product' => $highlightProduct, // Save as 1 or 0
        ]);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', __('Pricing table created successfully.'));
    }


}
