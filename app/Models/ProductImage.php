<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'product_id',
        'image_url'
    ];
}
