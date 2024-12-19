<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id'
    ];
}
