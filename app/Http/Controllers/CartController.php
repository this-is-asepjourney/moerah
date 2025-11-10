<?php
// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cartItems = $cart->items()->with('product.images')->get();
        
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->final_price;
        });

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string'
        ]);

        $product = Product::active()->findOrFail($productId);
        
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cart = $this->getOrCreateCart();
        
        // Check if item already in cart
        $existingItem = $cart->items()
            ->where('product_id', $productId)
            ->where('size', $request->size)
            ->where('color', $request->color)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $request->quantity,
                'size' => $request->size,
                'color' => $request->color,
                'price' => $product->final_price
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($itemId);

        if ($cartItem->product->stock < $request->quantity) {
            return response()->json(['error' => 'Stok tidak mencukupi'], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        $cart = $this->getOrCreateCart();
        $total = $cart->getTotal();

        return response()->json([
            'success' => true,
            'item_total' => $cartItem->quantity * $cartItem->price,
            'cart_total' => $total
        ]);
    }

    public function remove($itemId)
    {
        $cartItem = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($itemId);

        $cartItem->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang');
    }
    // app/Http/Controllers/CartController.php - Tambahkan method ini
    public function getCount()
    {
    $cart = $this->getOrCreateCart();
    $count = $cart->items()->sum('quantity');
    
    return response()->json(['count' => $count]);
    }

    private function getOrCreateCart()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id()]);
        }
        
        return $cart;
    }
}