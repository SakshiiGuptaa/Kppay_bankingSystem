<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAccountProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_description',
        'product_image',
        'product_tax_code',
        'product_type',
        'product_price',
        'tax_price',
        'billing_period',
    ];
}
?>