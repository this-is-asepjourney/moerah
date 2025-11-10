<!-- resources/views/admin/products/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-eye me-2"></i>Detail Produk: {{ $product->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Gambar Produk</h5>
            </div>
            <div class="card-body">
                @if($product->images->count() > 0)
                    <div class="row">
                        @foreach($product->images as $image)
                            <div class="col-6 mb-3">
                                <img src="{{ Storage::url($image->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid rounded {{ $image->is_main ? 'border border-primary' : '' }}"
                                     style="max-height: 200px; object-fit: cover;">
                                @if($image->is_main)
                                    <div class="text-center mt-1">
                                        <span class="badge bg-primary">Gambar Utama</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">Tidak ada gambar</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Produk</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama Produk</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>SKU</th>
                        <td>{{ $product->sku }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $product->category->name }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>
                            <strong>Rp {{ number_format($product->price) }}</strong>
                            @if($product->sale_price)
                                <br><small class="text-danger">Sale: Rp {{ number_format($product->sale_price) }}</small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>
                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            @if($product->is_featured)
                                <span class="badge bg-warning ms-1">Featured</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ukuran Tersedia</th>
                        <td>
                            @if($product->sizes)
                                {{ implode(', ', json_decode($product->sizes)) }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Warna Tersedia</th>
                        <td>
                            @if($product->colors)
                                {{ implode(', ', json_decode($product->colors)) }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $product->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Deskripsi</h5>
            </div>
            <div class="card-body">
                <p>{{ $product->description }}</p>
            </div>
        </div>
    </div>
</div>
@endsection