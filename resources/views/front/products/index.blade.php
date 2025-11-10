<!-- resources/views/front/products/index.blade.php -->
@extends('layouts.app')

@section('title', 'Produk - FashionStore')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Produk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category" class="form-select" onchange="document.getElementById('filter-form').submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->active_products_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Harga Minimum</label>
                            <input type="number" name="min_price" class="form-control" 
                                   value="{{ request('min_price') }}" placeholder="0" min="0">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Harga Maksimum</label>
                            <input type="number" name="max_price" class="form-control" 
                                   value="{{ request('max_price') }}" placeholder="1000000" min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Terapkan Filter
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-times me-2"></i>Reset Filter
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Koleksi Produk Kami</h2>
                <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           value="{{ request('search') }}" placeholder="Cari produk...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Filter Active Badges -->
            @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price']))
            <div class="mb-3">
                <small class="text-muted">Filter aktif:</small>
                @if(request('search'))
                    <span class="badge bg-primary me-1">Pencarian: "{{ request('search') }}"</span>
                @endif
                @if(request('category'))
                    @php $activeCategory = $categories->where('id', request('category'))->first(); @endphp
                    @if($activeCategory)
                        <span class="badge bg-primary me-1">Kategori: {{ $activeCategory->name }}</span>
                    @endif
                @endif
                @if(request('min_price'))
                    <span class="badge bg-primary me-1">Min: Rp {{ number_format(request('min_price')) }}</span>
                @endif
                @if(request('max_price'))
                    <span class="badge bg-primary me-1">Max: Rp {{ number_format(request('max_price')) }}</span>
                @endif
            </div>
            @endif

            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            @include('components.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4>Produk tidak ditemukan</h4>
                    <p class="text-muted">Coba gunakan kata kunci atau filter yang berbeda</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Semua Produk</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection