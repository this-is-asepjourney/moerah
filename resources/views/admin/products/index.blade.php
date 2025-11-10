<!-- resources/views/admin/products/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-tshirt me-2"></i>Kelola Produk</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Produk
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.products.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock" class="form-select">
                        <option value="">Semua Stok</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
<form action="{{ route('admin.products.bulk-action') }}" method="POST" id="bulk-form">
    @csrf
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Produk</h5>
            <div class="d-flex">
                <select name="action" class="form-select me-2" style="width: auto;">
                    <option value="">Pilih Aksi</option>
                    <option value="activate">Aktifkan</option>
                    <option value="deactivate">Nonaktifkan</option>
                    <option value="delete">Hapus</option>
                </select>
                <button type="submit" class="btn btn-outline-primary" id="bulk-action-btn">
                    Terapkan
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>SKU</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox">
                                </td>
                                <td>
                                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->is_featured)
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    @endif
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    <strong>Rp {{ number_format($product->price) }}</strong>
                                    @if($product->sale_price)
                                        <br><small class="text-danger">Sale: Rp {{ number_format($product->sale_price) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>{{ $product->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.products.show', $product->id) }}" 
                                           class="btn btn-sm btn-info" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Hapus produk ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tshirt fa-4x text-muted mb-3"></i>
                    <h5>Belum ada produk</h5>
                    <p class="text-muted">Mulai dengan menambahkan produk pertama Anda</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </a>
                </div>
            @endif
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk action form
    const bulkForm = document.getElementById('bulk-form');
    const bulkActionBtn = document.getElementById('bulk-action-btn');
    
    bulkForm.addEventListener('submit', function(e) {
        const selectedAction = this.action.value;
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        
        if (!selectedAction) {
            e.preventDefault();
            alert('Pilih aksi terlebih dahulu');
            return;
        }
        
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu produk');
            return;
        }
        
        if (selectedAction === 'delete') {
            if (!confirm('Yakin ingin menghapus produk yang dipilih?')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endpush
@endsection