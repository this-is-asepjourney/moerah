<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // GUNAKAN where() SEBAGAI GANTI scopeActive() dan scopeFeatured()
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category', 'images')
            ->limit(8)
            ->get();

        $newProducts = Product::where('is_active', true)
            ->with('category', 'images')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('front.home', compact('featuredProducts', 'newProducts'));
    }
}