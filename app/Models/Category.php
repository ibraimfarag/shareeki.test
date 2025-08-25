<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'category_id')->whereNotNull('category_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getImgPathAttribute()
    {
        return asset('storage/main/categories/'. $this->image);
    }
}
