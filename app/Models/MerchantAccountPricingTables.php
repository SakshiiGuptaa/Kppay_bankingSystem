<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAccountPricingTables extends Model
{
    use HasFactory;

    protected $table = 'merchant_account_pricing_tables';

    protected $fillable = [
        'user_id',
        'product_name',
        'default_view',
        'language',
        'background_color',
        'button_color',
        'font',
        'button_shape',
        'highlight_product',
    ];
}

?>