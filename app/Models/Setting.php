<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'key',
        'name',
        'value',
    ];
}
