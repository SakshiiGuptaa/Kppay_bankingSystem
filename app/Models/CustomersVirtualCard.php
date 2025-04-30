<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomersVirtualCard extends Model
{
    use HasFactory;

    protected $table = 'customers_virtual_card';

    protected $fillable = [
        'user_id',
        'card_name',
        'card_type',
        'card_number',
        'card_expiry',
        'card_cvv',
        'country',
    ];

    /**
     * Relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

?>