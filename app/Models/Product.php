<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Product extends Model
{
    protected $fillable = [
        'title',
        'price', 
        'description'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
