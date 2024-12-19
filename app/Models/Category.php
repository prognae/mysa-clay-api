<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'name',
        'slug'
    ];

    protected static function booted()
    {
        static::created(function ($category) {
            $array = explode(' ', strtolower($category->name));

            $category->slug = implode('-', $array);
            
            $category->save();
        });
    }
}
