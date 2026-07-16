<!-- header start -->
<nav class="navbar">
  <a href="{{ route('home') }}" class="logo">
    <span class="j">J</span><span class="an">an</span><span class="go">go</span><span class="k">K</span><span class="ids">ids</span>
  </a>
  <ul class="nav-menu">
    @if(isset($mainMenus) && $mainMenus->isNotEmpty())
        @foreach($mainMenus as $menu)
            <li>
                <a href="{{ $menu->url }}" class="{{ Str::contains(strtolower($menu->name), ['shop', 'now', 'buy']) ? 'shop-btn' : '' }}">
                    {{ $menu->name }}
                </a>
                @if($menu->children->isNotEmpty())
                    <ul>
                        @foreach($menu->children as $child)
                            <li>
                                <a href="{{ $child->url }}">{{ $child->name }}</a>
                                @if($child->children->isNotEmpty())
                                    <ul>
                                        @foreach($child->children as $subChild)
                                            <li><a href="{{ $subChild->url }}">{{ $subChild->name }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    @else
        <!-- Fallback static menus mapping to actual routes -->
        <li><a href="{{ route('products.index') }}">New Arrivals</a></li>
        <li><a href="{{ route('category.show', 'boys-clothing') }}">Boys</a></li>
        <li><a href="{{ route('category.show', 'girls-clothing') }}">Girls</a></li>
        <li><a href="{{ route('products.index', ['search' => 'baby']) }}">Babies</a></li>
        <li><a href="{{ route('products.index', ['filter' => 'sale']) }}">Sale 🔥</a></li>
        <li><a href="{{ route('products.index') }}" class="shop-btn">Shop Now ›</a></li>
    @endif
  </ul>
  <div class="nav-actions">
    <!-- Search button triggers searchModal -->
    <button class="icon-btn" data-bs-toggle="modal" data-bs-target="#searchModal" title="Search">
      <i class="ri-search-line"></i>
    </button>
    
    <!-- Wishlist button redirects to wishlist index -->
    <a href="{{ route('wishlist.index') }}" class="icon-btn" style="position:relative" title="Wishlist">
      <i class="ri-heart-line"></i>
      @if(($sharedWishlistCount ?? 0) > 0)
        <span class="badge" id="wishlist-count-badge">{{ $sharedWishlistCount }}</span>
      @endif
    </a>
    
    <!-- Cart button triggers cartOffcanvas -->
    <button class="icon-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" style="position:relative" title="Cart">
      <i class="ri-shopping-cart-line"></i>
      <span class="badge cart_qty_cls" id="offcanvas-cart-badge" style="{{ ($sharedCartCount ?? 0) > 0 ? '' : 'display: none;' }}">{{ $sharedCartCount ?? 0 }}</span>
    </button>
    
    <!-- User Account drop menu -->
    <div class="account-menu-container">
      <button class="icon-btn" id="accountMenuBtn" title="Account">
        <i class="ri-user-line"></i>
      </button>
      <div class="account-dropdown" id="accountDropdown">
        @guest
          <h6>Account</h6>
          <ul>
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
          </ul>
        @else
          <h6>{{ Auth::user()->name }}</h6>
          <ul>
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
            <li>
              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>
          </ul>
        @endguest
      </div>
    </div>
  </div>
</nav>

<!-- JavaScript to control the account dropdown toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('accountMenuBtn');
  const dropdown = document.getElementById('accountDropdown');
  if (btn && dropdown) {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdown.classList.toggle('show');
    });
    document.addEventListener('click', function(e) {
      if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
      }
    });
  }
});
</script>
<!-- header end -->
