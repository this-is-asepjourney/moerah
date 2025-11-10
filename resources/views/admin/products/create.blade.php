<!-- resources/views/admin/products/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus me-2"></i>Tambah Produk Baru</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Informasi Dasar</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                                  rows="5" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">SKU <span class="text-danger">*</span></label>
                                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                                       value="{{ old('sku') }}" required>
                                                @error('sku')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Harga & Stok</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Harga Normal <span class="text-danger">*</span></label>
                                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                                       value="{{ old('price') }}" min="0" step="0.01" required>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Harga Sale</label>
                                                <input type="number" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" 
                                                       value="{{ old('sale_price') }}" min="0" step="0.01">
                                                @error('sale_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Stok <span class="text-danger">*</span></label>
                                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                               value="{{ old('stock', 0) }}" min="0" required>
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Variants -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Varian Produk</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Ukuran Tersedia</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="S" id="size_s">
                                                    <label class="form-check-label" for="size_s">S</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="M" id="size_m">
                                                    <label class="form-check-label" for="size_m">M</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="L" id="size_l">
                                                    <label class="form-check-label" for="size_l">L</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="XL" id="size_xl">
                                                    <label class="form-check-label" for="size_xl">XL</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="XXL" id="size_xxl">
                                                    <label class="form-check-label" for="size_xxl">XXL</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Warna Tersedia</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="colors[]" value="Putih" id="color_white">
                                                    <label class="form-check-label" for="color_white">Putih</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="colors[]" value="Hitam" id="color_black">
                                                    <label class="form-check-label" for="color_black">Hitam</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="colors[]" value="Merah" id="color_red">
                                                    <label class="form-check-label" for="color_red">Merah</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="colors[]" value="Biru" id="color_blue">
                                                    <label class="form-check-label" for="color_blue">Biru</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="colors[]" value="Hijau" id="color_green">
                                                    <label class="form-check-label" for="color_green">Hijau</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <!-- Status -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                        <label class="form-check-label" for="is_active">Produk Aktif</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured">
                                        <label class="form-check-label" for="is_featured">Produk Unggulan</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Images -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Gambar Produk</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Upload Gambar</label>
                                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                        <small class="text-muted">Pilih multiple gambar. Gambar pertama akan menjadi gambar utama.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="card">
                                <div class="card-body">
                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-save me-2"></i>Simpan Produk
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection