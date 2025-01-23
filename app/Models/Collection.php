<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'name',
        'description',
        'thumbnail_url',
        'thumbnail_banner_url',
        'status',
        'is_featured',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
