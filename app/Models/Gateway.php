<?php

namespace App\Models;

use App\Models\Country;
use App\Enums\GatewayType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => GatewayType::class,
    ];

    public function scopeCode($query, $code)
    {
        return $query->where('gateway_code', $code);
    }
    
    //Gateway model to link it with the Country model based on the country_code
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'country_code');
    }

}
