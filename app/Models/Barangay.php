<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $table = 'barangays';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'code',
        'name',
        'old_name',
        'sub_municipality_code',
        'municipality_code',
        'district_code',
        'province_code',
        'region_code',
        'island_group_code',
    ];
}
