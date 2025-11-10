<!-- resources/views/customer/cart/index.blade.php -->
@extends('layouts.app')

@section('title', 'Keranjang Belanja - FashionStore')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Keranjang Belanja</h1>

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Item di Keranjang ({{ $cartItems->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="row align-items-center mb-4 pb-4 border-bottom">
                                <div class="col-md-2">
                                    <img src="{{ $item->product->main_image }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="img-fluid rounded">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <p class="text-muted small mb-1">SKU: {{ $item->product->sku }}</p>
                                    @if($item->size)
                                        <p class="small mb-1">Ukuran: {{ $item->size }}</p>
                                    @endif
                                    @if($item->color)
                                        <p class="small mb-0">Warna: {{ $item->color }}</p>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">Rp {{ number_format($item->price) }}</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                               class="form-control quantity-input" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->product->stock }}"
                                               data-item-id="{{ $item->id }}">
                                    </div>
                                    <small class="text-muted">Stok: {{ $item->product->stock }}</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold item-total">Rp {{ number_format($item->subtotal) }}</span>
                                </div>
                                <div class="col-md-1">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Hapus item dari keranjang?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal">Rp {{ number_format($total) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkos Kirim:</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="cart-total">Rp {{ number_format($total) }}</strong>
                        </div>
                        <a href="/checkout" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Lanjut ke Checkout
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary w-100 mt-2">
                            <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h3>Keranjang Belanja Kosong</h3>
            <p class="text-muted mb-4">Yuk, temukan produk menarik dan mulai belanja!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.getAttribute('data-item-id');
            const quantity = this.value;
            
            fetch(`/cart/update/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update item total
                    const itemTotal = this.closest('.row').querySelector('.item-total');
                    itemTotal.textContent = 'Rp ' + data.item_total.toLocaleString();
                    
                    // Update cart totals
                    document.getElementById('cart-subtotal').textContent = 'Rp ' + data.cart_total.toLocaleString();
                    document.getElementById('cart-total').textContent = 'Rp ' + data.cart_total.toLocaleString();
                    
                    // Update cart count in navbar
                    updateCartCount();
                } else {
                    alert('Error: ' + data.error);
                    this.value = this.defaultValue;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate quantity');
                this.value = this.defaultValue;
            });
        });
    });
});
</script>
@endpush
@endsection