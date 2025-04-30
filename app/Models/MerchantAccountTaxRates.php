<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAccountTaxRates extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow Laravel conventions
    protected $table = 'merchant_account_tax_rates';

    // Fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'type',
        'region',
        'rate',
        'include_tax',
        'description',
    ];
}

?>