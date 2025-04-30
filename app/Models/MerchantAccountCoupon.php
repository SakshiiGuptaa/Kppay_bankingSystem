<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAccountCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Add user_id here
        'name',
        'coupon_id',
        'type',
        'percentage_off',
        'apply_specific_products',
        'duration',
        'limit_date_range',
        'limit_redemption',
        'use_customer_codes',
        'coupon_code',
        'first_time_only',
        'specific_customer',
        'redemption_limit',
        'add_expiry',
        'min_order_value',
    ];
}
?>