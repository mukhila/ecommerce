<!-- header start -->
<nav class="navbar">
  <a href="{{ route('home') }}" class="logo">
    <span class="j">J</span><span class="an">an</span><span class="go">go</span><span class="k">K</span><span class="ids">ids</span>
  </a>
  <ul class="nav-menu">
    @if(isset($mainMenus) && $mainMenus->isNotEmpty())
        @foreach($mainMenus as $menu)
            <li class="{{ $menu->children->isNotEmpty() ? 'has-dropdown' : '' }}">
                <a href="{{ $menu->url }}" class="{{ Str::contains(strtolower($menu->name), ['shop', 'now', 'buy']) ? 'shop-btn' : '' }}">
                    {{ $menu->name }}
                </a>
                @if($menu->children->isNotEmpty())
                    <ul>
                        @foreach($menu->children as $child)
                            <li class="{{ $child->children->isNotEmpty() ? 'has-dropdown' : '' }}">
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
        <li><a href="{{ route('products.index', ['search' => 'baby']) }}">Babies</a></li>
        <li><a href="{{ route('products.index', ['filter' => 'sale']) }}">Sale 🔥</a></li>
        <li><a href="{{ auth()->check() ? route('support.index') : route('support.create') }}">Support</a></li>
        <li><a href="{{ route('products.index') }}" class="shop-btn">Shop Now ›</a></li>
    @endif
  </ul>
  <div class="nav-actions">
    <!-- Desktop-only: Wishlist + Account (hidden on mobile — both live in the slide-out nav) -->
    <div class="desktop-nav-icons d-none d-md-flex align-items-center gap-3">
      <a href="{{ route('wishlist.index') }}" class="icon-btn nav-wishlist-btn" style="position:relative" title="Wishlist">
        <i class="ri-heart-line"></i>
        @if(($sharedWishlistCount ?? 0) > 0)
          <span class="badge" id="wishlist-count-badge">{{ $sharedWishlistCount }}</span>
        @endif
      </a>

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

    <!-- Cart button (always visible) -->
    <button class="icon-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" style="position:relative" title="Cart">
      <i class="ri-shopping-cart-line"></i>
      <span class="badge cart_qty_cls" id="offcanvas-cart-badge" style="{{ ($sharedCartCount ?? 0) > 0 ? '' : 'display: none;' }}">{{ $sharedCartCount ?? 0 }}</span>
    </button>

    <!-- Hamburger (mobile only) -->
    <button class="hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
</nav>

<!-- ── MOBILE NAV PANEL ───────────────────────── -->
<div class="mobile-nav" id="mobileNav" aria-hidden="true">
  <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
  <div class="mobile-nav-panel" role="dialog" aria-label="Navigation menu">
    <div class="mobile-nav-header">
      <a href="{{ route('home') }}" class="mobile-nav-logo">
        <span class="j">J</span><span class="an">an</span><span class="go">go</span><span class="k">K</span><span class="ids">ids</span>
      </a>
      <button class="mobile-nav-close-btn" id="mobileNavClose" aria-label="Close menu">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <ul class="mobile-nav-list">
      @if(isset($mainMenus) && $mainMenus->isNotEmpty())
        @foreach($mainMenus as $menu)
          <li>
            @if($menu->children->isNotEmpty())
              <div class="mobile-nav-item-row">
                <a href="{{ $menu->url }}">{{ $menu->name }}</a>
                <button class="mobile-submenu-toggle" aria-label="Toggle submenu">
                  <i class="ri-arrow-down-s-line"></i>
                </button>
              </div>
              <ul class="mobile-submenu">
                @foreach($menu->children as $child)
                  <li><a href="{{ $child->url }}">{{ $child->name }}</a></li>
                @endforeach
              </ul>
            @else
              <a href="{{ $menu->url }}" class="mobile-nav-simple-link">{{ $menu->name }}</a>
            @endif
          </li>
        @endforeach
      @else
        <li><a href="{{ route('products.index') }}" class="mobile-nav-simple-link">New Arrivals</a></li>
        <li><a href="{{ route('category.show', 'boys-clothing') }}" class="mobile-nav-simple-link">Boys</a></li>
        <li><a href="{{ route('products.index', ['search' => 'baby']) }}" class="mobile-nav-simple-link">Babies</a></li>
        <li><a href="{{ route('products.index', ['filter' => 'sale']) }}" class="mobile-nav-simple-link">Sale 🔥</a></li>
        <li><a href="{{ auth()->check() ? route('support.index') : route('support.create') }}" class="mobile-nav-simple-link">Support</a></li>
      @endif

      {{-- ── Account & Wishlist nav items ── --}}
      <li class="mobile-nav-sep" aria-hidden="true"></li>

      @guest
        <li>
          <a href="{{ route('login') }}" class="mobile-nav-simple-link mobile-nav-user-link">
            <i class="ri-user-line"></i> Login
          </a>
        </li>
        <li>
          <a href="{{ route('register') }}" class="mobile-nav-simple-link mobile-nav-user-link">
            <i class="ri-user-add-line"></i> Register
          </a>
        </li>
      @else
        <li>
          <a href="{{ route('dashboard') }}" class="mobile-nav-simple-link mobile-nav-user-link">
            <i class="ri-user-line"></i> My Account
          </a>
        </li>
        <li>
          <a href="{{ route('wishlist.index') }}" class="mobile-nav-simple-link mobile-nav-user-link">
            <i class="ri-heart-line"></i> Wishlist
            @if(($sharedWishlistCount ?? 0) > 0)
              <span class="mobile-nav-badge">{{ $sharedWishlistCount }}</span>
            @endif
          </a>
        </li>
      @endguest
    </ul>

    <div class="mobile-nav-footer">
      @auth
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">
          <i class="ri-logout-box-line"></i> Logout
        </a>
        <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
      @endauth
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // ── Account dropdown ─────────────────────────
  const accountBtn      = document.getElementById('accountMenuBtn');
  const accountDropdown = document.getElementById('accountDropdown');
  if (accountBtn && accountDropdown) {
    accountBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      accountDropdown.classList.toggle('show');
    });
    document.addEventListener('click', function (e) {
      if (!accountBtn.contains(e.target) && !accountDropdown.contains(e.target)) {
        accountDropdown.classList.remove('show');
      }
    });
  }

  // ── Mobile nav open/close ────────────────────
  const hamburger      = document.getElementById('hamburgerBtn');
  const mobileNav      = document.getElementById('mobileNav');
  const mobileOverlay  = document.getElementById('mobileNavOverlay');
  const mobileClose    = document.getElementById('mobileNavClose');

  function openMobileNav() {
    mobileNav.classList.add('open');
    mobileNav.setAttribute('aria-hidden', 'false');
    hamburger.classList.add('open');
    hamburger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeMobileNav() {
    mobileNav.classList.remove('open');
    mobileNav.setAttribute('aria-hidden', 'true');
    hamburger.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (hamburger)     hamburger.addEventListener('click', openMobileNav);
  if (mobileClose)   mobileClose.addEventListener('click', closeMobileNav);
  if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobileNav);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMobileNav();
  });

  // ── Mobile submenu accordions ────────────────
  document.querySelectorAll('.mobile-submenu-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const submenu = this.closest('li').querySelector('.mobile-submenu');
      if (!submenu) return;
      const isOpen = submenu.classList.contains('open');
      // Close all open submenus first
      document.querySelectorAll('.mobile-submenu.open').forEach(function (el) {
        el.classList.remove('open');
      });
      document.querySelectorAll('.mobile-submenu-toggle.open').forEach(function (el) {
        el.classList.remove('open');
      });
      if (!isOpen) {
        submenu.classList.add('open');
        this.classList.add('open');
      }
    });
  });
});
</script>
<!-- header end -->
