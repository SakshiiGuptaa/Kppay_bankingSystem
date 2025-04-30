<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAccount extends Model
{
    use HasFactory;

    protected $table = 'master_account';

    protected $fillable = [
        'datetime',
        'account_no',
        'type',
        'from',
        'amount',
        'currency',
        'view_details',
        'created_at',
        'updated_at',
    ];
}
