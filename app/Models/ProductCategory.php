<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'product_id',
        'category_id'
    ];
}
