<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'path'
    ];
}
