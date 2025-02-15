<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'collection_id',
        'price',
        'quantity',
        'status',
        'thumbnail_url',
        'is_discounted',
        'discounted_price',
        'markup',
        'markdown',
        'final_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function views()
    {
        return $this->belongsToMany(User::class, 'product_views');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function collection()
    {
        return $this->hasOne(Collection::class, 'id', 'collection_id');
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }
}
