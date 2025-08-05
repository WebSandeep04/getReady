<!-- Header -->
<header>
  <nav class="top-nav d-flex justify-content-between align-items-center px-3 py-2 bg-light">
    <a href="{{ url('/') }}" class="logo font-weight-bold text-warning h4" style="text-decoration: none;">
      @if(frontend_setting('site_logo'))
        <img src="{{ asset(frontend_setting('site_logo')) }}" alt="{{ frontend_setting('site_logo_alt', 'GetReady Logo') }}" style="height: 40px; margin-right: 10px;">
      @endif
      <!-- {{ frontend_setting('footer_title', 'GET Ready') }} -->
    </a>
    @if(isset($showFilters) ? $showFilters : true)
    <div class="menu d-flex">
      <select class="form-control mx-1 dropdown" name="category_filter">
        <option value="">Categories</option>
        @foreach($filters['categories'] as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
      <select class="form-control mx-1 dropdown" name="fabric_filter">
        <option value="">Fabric</option>
        @foreach($filters['fabric_types'] as $fabric)
          <option value="{{ $fabric->id }}">{{ $fabric->name }}</option>
        @endforeach
      </select>
      <select class="form-control mx-1 dropdown" name="color_filter">
        <option value="">Color</option>
        @foreach($filters['colors'] as $color)
          <option value="{{ $color->id }}">{{ $color->name }}</option>
        @endforeach
      </select>
      <select class="form-control mx-1 dropdown" name="size_filter">
        <option value="">Size</option>
        @foreach($filters['sizes'] as $size)
          <option value="{{ $size->id }}">{{ $size->name }}</option>
        @endforeach
      </select>
      <select class="form-control mx-1 dropdown" name="bottom_type_filter">
        <option value="">Bottom Type</option>
        @foreach($filters['bottom_types'] as $bottomType)
          <option value="{{ $bottomType->id }}">{{ $bottomType->name }}</option>
        @endforeach
      </select>
      <select class="form-control mx-1 dropdown" name="price_range">
        <option value="">Price Range</option>
        <option value="0-500">₹0 - ₹500</option>
        <option value="500-1000">₹500 - ₹1000</option>
        <option value="1000-2000">₹1000 - ₹2000</option>
        <option value="2000-5000">₹2000 - ₹5000</option>
        <option value="5000+">₹5000+</option>
      </select>
    </div>
    @endif
    <div class="auth-buttons">
      @if(Auth::check())
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-outline-secondary btn-sm mx-1">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm mx-1">Login</a>
      @endif
      @if(Auth::check())
      <div class="dropdown d-inline-block mx-1">
        <a href="#" class="btn btn-outline-secondary btn-sm position-relative dropdown-toggle" data-toggle="dropdown" title="Notifications" id="notification-toggle">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16" style="vertical-align:middle;">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.322 3.94l-.01.568L8 10.5l4.332-1.002-.01-.568C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.455.803.694 1.097-.268.196-.83.629-1.956 1.456A12.984 12.984 0 0 1 8 14.5c-1.892 0-3.56-.446-4.958-1.947-.418-.48.053-.954.952-.954.192 0 .362.103.518.204.134.099.28.206.44.315.195.132.418.267.665.403.414.243.751.445 1.093.414.26-.02.504-.145.732-.291.215-.136.414-.28.596-.436.18-.156.315-.296.406-.413.09-.117.138-.186.138-.186z"/>
          </svg>
          <span id="notification-badge" class="badge badge-danger badge-pill position-absolute" style="top: -8px; right: -8px; font-size: 0.6rem;">
            {{ Auth::user()->unreadNotificationsCount() }}
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" style="min-width: 300px; max-height: 400px; overflow-y: auto;" id="notification-dropdown">
          <div class="dropdown-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Notifications</h6>
            <button class="btn btn-sm btn-outline-primary" id="mark-all-read">Mark All Read</button>
          </div>
          <div class="dropdown-divider"></div>
          <div id="notifications-list">
            <div class="text-center py-3">
              <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
              </div>
              <div class="mt-2">Loading notifications...</div>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-center text-primary" href="#">
            <small>View All Notifications</small>
          </a>
        </div>
      </div>
      @endif
      @if(Auth::check())
      <a href="{{ route('cart') }}" class="btn btn-outline-secondary btn-sm mx-1 position-relative" title="Cart">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16" style="vertical-align:middle;">
          <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zm3.14 4l1.25 6.5h7.22l1.25-6.5H3.14zM5.5 16a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm7 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
        </svg>
        <span id="cart-count" class="badge badge-danger badge-pill position-absolute" style="top: -8px; right: -8px; font-size: 0.6rem;">
          {{ Auth::user()->cartItems()->count() }}
        </span>
      </a>
      @endif
      <a href="{{ route('sell') }}" class="btn btn-sell btn-warning btn-sm mx-1">Sell</a>
      @if(Auth::check())
      <div class="dropdown d-inline-block mx-2">
        <a href="#" class="fw-bold dropdown-toggle" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none;">
          <span class="rounded-circle d-inline-block text-center align-middle bg-secondary text-white" style="width:36px; height:36px; line-height:36px; font-size:1.2rem; overflow:hidden; vertical-align:middle;">
            @if(Auth::user()->profile_image)
              <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
            @else
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            @endif
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="{{ route('listed.clothes') }}">Listed Clothes</a>
          <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
        </div>
      </div>
      @endif
    </div>
  </nav>

  <!-- @if(isset($showHero) && $showHero)
  <div class="hero text-center text-white py-5">
    <h1>Sell and Purchase <br>Occasional Wears <span class="text-warning">Online</span></h1>
    <button class="btn btn-warning mt-3">Explore Now</button>
  </div>
  @endif -->
</header> 