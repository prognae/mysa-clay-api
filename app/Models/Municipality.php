<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $table = 'municipalities';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'code',
        'name',
        'old_name',
        'is_capital',
        'district_code',
        'province_code',
        'island_group_code',
        'area_code',
    ];
}
