<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // GUNAKAN where('is_active', true) SEBAGAI GANTI scopeActive()
        $query = Product::where('is_active', true)->with('category', 'images');
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Filter by price range
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // GUNAKAN where('is_active', true) UNTUK CATEGORIES JUGA
        $categories = Category::where('is_active', true)->get();

        return view('front.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        // GUNAKAN where('is_active', true) SEBAGAI GANTI scopeActive()
        $product = Product::where('is_active', true)
            ->with('category', 'images')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0) // GUNAKAN where() SEBAGAI GANTI scopeInStock()
            ->limit(4)
            ->get();

        return view('front.products.show', compact('product', 'relatedProducts'));
    }
}