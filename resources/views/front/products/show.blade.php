<!-- resources/views/front/products/show.blade.php -->
@extends('layouts.app')

@section('title', $product->name . ' - FashionStore')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <img src="{{ $product->main_image }}" class="img-fluid rounded" 
                         alt="{{ $product->name }}" id="main-image">
                    
                    @if($product->images->count() > 1)
                    <div class="row mt-3">
                        @foreach($product->images as $image)
                            <div class="col-3">
                                <img src="{{ $image->image_path }}" 
                                     class="img-thumbnail product-thumbnail"
                                     style="cursor: pointer; height: 80px; object-fit: cover;"
                                     onclick="document.getElementById('main-image').src = this.src">
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="h3">{{ $product->name }}</h1>
                    <p class="text-muted">SKU: {{ $product->sku }}</p>
                    
                    <div class="mb-3">
                        @if($product->has_sale)
                            <span class="h4 text-danger fw-bold">Rp {{ number_format($product->sale_price) }}</span>
                            <span class="h5 text-muted text-decoration-line-through ms-2">Rp {{ number_format($product->price) }}</span>
                            <span class="badge bg-danger ms-2">Sale</span>
                        @else
                            <span class="h4 fw-bold">Rp {{ number_format($product->price) }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                            {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Stok Habis' }}
                        </span>
                        <span class="badge bg-info ms-1">{{ $product->category->name }}</span>
                    </div>

                    <p class="mb-4">{{ $product->description }}</p>

                    @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        
                        @if($product->sizes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ukuran</label>
                            <select name="size" class="form-select" required>
                                <option value="">Pilih Ukuran</option>
                                @foreach(json_decode($product->sizes) as $size)
                                    <option value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if($product->colors)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Warna</label>
                            <select name="color" class="form-select" required>
                                <option value="">Pilih Warna</option>
                                @foreach(json_decode($product->colors) as $color)
                                    <option value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah</label>
                            <input type="number" name="quantity" class="form-control" 
                                   value="1" min="1" max="{{ $product->stock }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                        </button>
                    </form>
                    @else
                    <button class="btn btn-secondary btn-lg w-100" disabled>
                        <i class="fas fa-times me-2"></i>Stok Habis
                    </button>
                    @endif

                    <div class="product-features">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-shipping-fast text-primary me-2"></i>
                            <span>Gratis Ongkir untuk order di atas Rp 500.000</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-undo-alt text-primary me-2"></i>
                            <span>Garansi 30 hari pengembalian</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <span>Pembayaran 100% aman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Produk Terkait</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-lg-3 col-md-6 mb-4">
                        @include('components.product-card', ['product' => $relatedProduct])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.product-thumbnail:hover {
    border-color: #e74c3c;
}
</style>
@endsection