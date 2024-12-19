<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];
}
