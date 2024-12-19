<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    protected $table = 'product_comments';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'product_id',
        'user_id',
        'comment'
    ];
}
