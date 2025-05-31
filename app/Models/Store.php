<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'description',
        'contact_info',
        'city',
        'status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
}