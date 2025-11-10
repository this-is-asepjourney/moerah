<!-- resources/views/front/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home - FashionStore')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Temukan Gaya Terbaik Anda</h1>
            <p class="lead mb-4">Koleksi fashion terkini dengan kualitas premium dan harga terjangkau</p>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Belanja Sekarang</a>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <h2 class="text-center">Produk Unggulan</h2>
                    <p class="text-center text-muted">Pilihan terbaik dari koleksi kami</p>
                </div>
            </div>
            <div class="row">
                @foreach($featuredProducts as $product)
                    <div class="col-lg-3 col-md-6 mb-4">
                        @include('components.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <h2 class="text-center">Produk Terbaru</h2>
                    <p class="text-center text-muted">Koleksi terbaru yang baru saja tiba</p>
                </div>
            </div>
            <div class="row">
                @foreach($newProducts as $product)
                    <div class="col-lg-3 col-md-6 mb-4">
                        @include('components.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Lihat Semua Produk</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                    </div>
                    <h4>Gratis Ongkir</h4>
                    <p class="text-muted">Gratis pengiriman untuk pembelian di atas Rp 500.000</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-undo-alt fa-3x text-primary"></i>
                    </div>
                    <h4>Garansi 30 Hari</h4>
                    <p class="text-muted">Garansi pengembalian 30 hari jika tidak sesuai</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h4>Pembayaran Aman</h4>
                    <p class="text-muted">Sistem pembayaran yang aman dan terpercaya</p>
                </div>
            </div>
        </div>
    </section>
@endsection