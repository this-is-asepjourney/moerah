<!-- resources/views/components/product-card.blade.php -->
<div class="card product-card h-100">
    <div class="position-relative">
        <img src="{{ $product->main_image }}" class="card-img-top" 
             alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
        @if($product->has_sale)
            <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 small rounded">
                SALE
            </span>
        @endif
        @if($product->is_featured)
            <span class="position-absolute top-0 end-0 bg-warning text-dark px-2 py-1 m-2 small rounded">
                <i class="fas fa-star me-1"></i>FEATURED
            </span>
        @endif
    </div>
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ Str::limit($product->name, 50) }}</h5>
        <p class="card-text text-muted small flex-grow-1">
            {{ Str::limit($product->description, 80) }}
        </p>
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                @if($product->has_sale)
                    <span class="text-danger fw-bold">Rp {{ number_format($product->sale_price) }}</span>
                    <small class="text-muted text-decoration-line-through d-block">Rp {{ number_format($product->price) }}</small>
                @else
                    <span class="fw-bold">Rp {{ number_format($product->price) }}</span>
                @endif
            </div>
            <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                {{ $product->stock > 0 ? $product->stock : 'Habis' }}
            </span>
        </div>

        <div class="mt-auto">
            <a href="{{ route('products.show', $product->slug) }}" 
               class="btn btn-outline-primary btn-sm w-100">
                <i class="fas fa-eye me-1"></i> Detail
            </a>
        </div>
    </div>
</div>