<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAccountFeature extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'feature_name', 'lookup_key', 'metadata'];

    // Automatically cast `metadata` as an array
    protected $casts = [
        'metadata' => 'array',
    ];
}

?>
