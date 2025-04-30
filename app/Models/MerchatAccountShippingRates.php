<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchatAccountShippingRates extends Model
{
    use HasFactory;
 
 // Specify the correct table name
    protected $table = 'merchant_account_shipping_rates';
    
    protected $fillable = [
        'user_id',
        'tax_price',
        'amount',
        'currency',
        'description',
        'min_days',
        'max_days',
        'tax_code',
    ];

}

?>