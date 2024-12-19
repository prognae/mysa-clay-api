<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IslandGroup extends Model
{
    protected $connection = 'mysql';

    protected $table = 'island_groups';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'code',
        'name'
    ];
}
