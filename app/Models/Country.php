<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    // Specify the table name if it's not in plural format
    protected $table = 'countries';

    // Allow mass assignment for these fields
    protected $fillable = ['country_code', 'country_name'];

    // Disable timestamps if your `countries` table does not have `created_at` and `updated_at`
    public $timestamps = false;
}
