<?php
// app/Http/Controllers/Admin/ProductController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'images');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by stock
        if ($request->has('stock') && $request->stock != '') {
            if ($request->stock == 'low') {
                $query->where('stock', '<', 10);
            } elseif ($request->stock == 'out') {
                $query->where('stock', 0);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

   public function create()
    {
    // GUNAKAN where('is_active', true) SEBAGAI GANTI scopeActive()
    $categories = Category::where('is_active', true)->get();
    return view('admin.products.create', compact('categories'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Generate slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'sizes' => $request->sizes ? json_encode($request->sizes) : null,
            'colors' => $request->colors ? json_encode($request->colors) : null,
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
            'category_id' => $request->category_id,
        ]);

        // Handle image upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $imagePath = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_main' => $key === 0, // First image as main
                    'order' => $key,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $product->load('category', 'images');
        return view('admin.products.show', compact('product'));
    }

   // Di method edit()
    public function edit(Product $product)
    {
    // GUNAKAN where('is_active', true) SEBAGAI GANTI scopeActive()
    $categories = Category::where('is_active', true)->get();
    $product->load('images');
    return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Update slug if name changed
        if ($product->name != $request->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        } else {
            $slug = $product->slug;
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'sizes' => $request->sizes ? json_encode($request->sizes) : null,
            'colors' => $request->colors ? json_encode($request->colors) : null,
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
            'category_id' => $request->category_id,
        ]);

        // Handle new image upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_main' => false,
                    'order' => $product->images()->count(),
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $productIds = $request->product_ids;

        if (!$productIds) {
            return back()->with('error', 'Pilih produk terlebih dahulu');
        }

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $productIds)->update(['is_active' => true]);
                $message = 'Produk berhasil diaktifkan';
                break;
            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['is_active' => false]);
                $message = 'Produk berhasil dinonaktifkan';
                break;
            case 'delete':
                Product::whereIn('id', $productIds)->delete();
                $message = 'Produk berhasil dihapus';
                break;
            default:
                return back()->with('error', 'Aksi tidak valid');
        }

        return back()->with('success', $message);
    }

    public function deleteImage(ProductImage $image)
    {
        // Delete from storage
        Storage::disk('public')->delete($image->image_path);
        
        // Delete from database
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function setMainImage(Product $product, ProductImage $image)
    {
        // Reset all images to not main
        $product->images()->update(['is_main' => false]);
        
        // Set this image as main
        $image->update(['is_main' => true]);

        return response()->json(['success' => true]);
    }
}