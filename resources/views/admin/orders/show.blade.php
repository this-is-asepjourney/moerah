<!-- resources/views/admin/orders/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-shopping-bag me-2"></i>Detail Pesanan: #{{ $order->id }}</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Item Pesanan</h5>
            </div>
            <div class="card-body">
                @if($order->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong>
                                        @if($item->size)
                                            <br><small>Ukuran: {{ $item->size }}</small>
                                        @endif
                                        @if($item->color)
                                            <br><small>Warna: {{ $item->color }}</small>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($item->price) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>Rp {{ number_format($order->total_amount) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">Tidak ada item dalam pesanan</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Status:</strong><br>
                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Customer:</strong><br>
                    {{ $order->user->name }}<br>
                    {{ $order->user->email }}
                </div>
                
                <div class="mb-3">
                    <strong>Tanggal Pesan:</strong><br>
                    {{ $order->created_at->format('d M Y H:i') }}
                </div>

                <!-- Update Status Form -->
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Update Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection