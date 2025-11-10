<?php
// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // TAMBAHKAN INI

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active'
    ];

    // TAMBAHKAN SCOPE INI
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getActiveProductsCountAttribute()
    {
        return $this->products()->where('is_active', true)->count();
    }

    // TAMBAHKAN ACCESSOR UNTUK IMAGE URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image); // SEKARANG Storage TERDEFINISI
        }
        return '/images/default-category.jpg';
    }
}