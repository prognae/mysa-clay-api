<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id',
        'full_name',
        'region_id',
        'province_id',
        'city_id',
        'barangay_id',
        'postal_code',
        'house_info',
        'type',
        'is_default',
    ];
}
