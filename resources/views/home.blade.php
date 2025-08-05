@extends('layouts.app')

@section('title', frontend_setting('site_title', 'Get Ready - Home'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/hero.css') }}">
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero text-center d-flex align-items-center justify-content-center" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset(frontend_setting('hero_image', 'images/main.png')) }}') center/cover; height: 100vh; min-height: 100vh;">
  <div class="container text-center d-flex flex-column align-items-center justify-content-center">
    <h1 class="text-white display-4 mb-3 text-center">{{ frontend_setting('hero_title', 'Welcome to GetReady') }}</h1>
    <h3 class="text-white mb-3 text-center">{{ frontend_setting('hero_subtitle', 'Your premier destination for fashion rental') }}</h3>
    <p class="text-white mb-4 text-center">{{ frontend_setting('hero_description', 'Discover amazing fashion pieces for your special occasions. Rent, wear, and return with ease.') }}</p>
    <a href="{{ frontend_setting('hero_button_url', '/clothes') }}" class="btn btn-warning btn-lg text-center">{{ frontend_setting('hero_button_text', 'Start Shopping') }}</a>
  </div>
</section>

<!-- About -->
<section class="about text-center py-4">
  <h2 class="text-warning">{{ frontend_setting('about_title', 'About Us') }}</h2>
  <p>{{ frontend_setting('about_content', 'Celebrate every occasion in style — without compromise. At GetReady, we make it easy to buy, sell, or rent premium outfits for weddings, festivals, and events. Smart fashion choices for modern wardrobes.') }}</p>
</section>

<!-- Most Loved -->
<section class="most-loved text-center py-4 bg-light">
  <h2 class="text-warning mb-3">Most Loved</h2>
  <div class="tabs mb-4">
    <button class="tab btn btn-outline-warning active" onclick="switchTab('men')">Men</button>
    <button class="tab btn btn-outline-warning" onclick="switchTab('women')">Women</button>
    <button class="tab btn btn-outline-warning" onclick="switchTab('kids')">Kids</button>
  </div>

  <div id="mostLovedCarousel" class="carousel slide w-75 mx-auto" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#mostLovedCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#mostLovedCarousel" data-slide-to="1"></li>
      <li data-target="#mostLovedCarousel" data-slide-to="2"></li>
    </ol>

    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assets/images/lehenga.jpg" class="d-block mx-auto" alt="Outfit 1" style="height: 400px;">
      </div>
      <div class="carousel-item">
        <img src="assets/images/lehenga.jpg" class="d-block mx-auto" alt="Outfit 2" style="height: 400px;">
      </div>
      <div class="carousel-item">
        <img src="assets/images/lehenga.jpg" class="d-block mx-auto" alt="Outfit 3" style="height: 400px;">
      </div>
    </div>

    <a class="carousel-control-prev" href="#mostLovedCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#mostLovedCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</section>

<!-- Occasion -->
<section class="occasion text-center py-4">
  <h2 class="text-warning mb-3">Choose Your Outfits According To Your Occasion</h2>
  <div class="occasion-tabs mb-3">
    <button class="btn btn-outline-secondary mx-1">Wedding</button>
    <button class="btn btn-outline-secondary mx-1">Corporate Event</button>
    <button class="btn btn-outline-secondary mx-1">Party</button>
    <button class="btn btn-outline-secondary mx-1">Others</button>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      @forelse($clothes as $cloth)
        <div class="col-6 col-md-3 mb-3">
          <div class="card h-100">
            <a href="{{ route('clothes.show', $cloth->id) }}">
              @if($cloth->images->count() > 0)
                <img src="{{ asset('storage/' . $cloth->images->first()->image_path) }}" alt="{{ $cloth->title }}" class="card-img-top">
              @else
                <img src="images/1.jpg" alt="{{ $cloth->title }}" class="card-img-top">
              @endif
            </a>
            <div class="card-body d-flex flex-column">
              <h6 class="card-title">{{ $cloth->title }}</h6>
              <p class="card-text text-warning fw-bold">₹{{ number_format($cloth->rent_price) }}</p>
              <button class="btn btn-warning btn-sm add-to-cart-btn mt-auto" data-cloth-id="{{ $cloth->id }}">
                <i class="bi bi-cart-plus me-1"></i>Rent
              </button>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center">
          <p>No clothes available at the moment.</p>
        </div>
      @endforelse
    </div>
  </div>

  <button class="btn btn-warning mt-3">Load More</button>
</section>
@endsection
