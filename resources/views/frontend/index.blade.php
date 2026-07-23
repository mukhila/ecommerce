@extends('layouts.master')

@section('title', 'Home')
@section('meta_description', 'Jango Kidswear - Shop premium kids clothing online. Trendy, comfortable & affordable fashion for boys & babies. Free shipping above ₹3000. COD available.')
@section('meta_keywords', 'kids fashion online India, children clothing, boys clothes, baby wear, affordable kids fashion, Jango Kidswear')
@section('og_title', 'Jango Kidswear | Premium Kids Fashion Online')
@section('og_description', 'Shop trendy and affordable kids clothing at Jango Kidswear. New arrivals every week. Free shipping above ₹3000.')
@section('og_type', 'website')
@section('og_url', url('/'))

@push('json_ld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "Jango Kidswear",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@@type": "SearchAction",
        "target": {
            "@@type": "EntryPoint",
            "urlTemplate": "{{ url('/search') }}?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>
@endpush

@section('content')

@php
    $slidesData = [];
    if (isset($sliders) && $sliders->isNotEmpty()) {
        foreach ($sliders as $index => $slider) {
            $slideIndex = $index % 3;
            $emojis = ['👕', '🧢', '🍼'];
            $pillIcons = ['🏷️', '⚡', '🌿'];
            $bgClasses = ['sl-1', 'sl-2', 'sl-4'];
            $btnLabels = ['Shop Now', 'Shop Boys', 'Shop Baby'];
            $badges = ['FLAT 30% OFF', 'NEW IN 2025', '100% ORGANIC'];
            
            $slidesData[] = [
                'bg_class' => $bgClasses[$slideIndex],
                'eyebrow' => $slider->subtitle ?: 'New Collection',
                'headline' => $slider->title,
                'desc' => 'Premium fashion collection crafted with love and comfort for your little ones.',
                'pill_icon' => $pillIcons[$slideIndex],
                'pill_label' => 'Starting from',
                'pill_value' => '₹299 onwards',
                'cta_url' => $slider->link ?: route('products.index'),
                'cta_text' => $btnLabels[$slideIndex],
                'emoji' => $emojis[$slideIndex],
                'badge' => $badges[$slideIndex],
                'card_title' => $slider->title,
                'image' => asset('uploads/'.$slider->image),
                'chips' => $slideIndex === 0 ? [
                    ['icon' => '⭐', 'label' => 'Rating', 'val' => '4.9 / 5.0', 'cls' => 'ci-yellow'],
                    ['icon' => '🚚', 'label' => 'Delivery', 'val' => '2-3 Days', 'cls' => 'ci-teal']
                ] : ($slideIndex === 1 ? [
                    ['icon' => '🏅', 'label' => 'Quality', 'val' => 'Premium+', 'cls' => 'ci-yellow'],
                    ['icon' => '⚡', 'label' => 'Flash Sale', 'val' => 'Up to 50% OFF', 'cls' => 'ci-coral']
                ] : [
                    ['icon' => '🌿', 'label' => 'Material', 'val' => '100% Organic', 'cls' => 'ci-teal'],
                    ['icon' => '🏆', 'label' => 'Certified', 'val' => 'GOTS Organic', 'cls' => 'ci-yellow']
                ])
            ];
        }
    } else {
        // Fallback static slides — boys & baby only
        $slidesData = [
            [
                'bg_class' => 'sl-1',
                'eyebrow' => '☀️ Boys Summer 2025',
                'headline' => 'Cool Styles For<br><span class="line-accent">Bold Boys</span>',
                'desc' => 'Vibrant, breathable summer styles crafted for boys who love to run, jump, and explore every single sunny day.',
                'pill_icon' => '🏷️',
                'pill_label' => 'Starting from',
                'pill_value' => '₹299 onwards',
                'cta_url' => route('products.index'),
                'cta_text' => '👦 Shop Summer',
                'emoji' => '👕',
                'badge' => 'FLAT<br>30%<br>OFF',
                'card_title' => 'Summer Tee Collection',
                'chips' => [
                    ['icon' => '⭐', 'label' => 'Rating', 'val' => '4.9 / 5.0', 'cls' => 'ci-yellow'],
                    ['icon' => '🚚', 'label' => 'Delivery', 'val' => '2–3 Days', 'cls' => 'ci-teal']
                ]
            ],
            [
                'bg_class' => 'sl-2',
                'eyebrow' => '🚀 Boys Collection 2025',
                'headline' => 'Adventure<br>Starts With<br><span class="line-accent">Bold Style</span>',
                'desc' => 'From sporty casuals to school-ready looks — everything your little explorer needs to conquer the day in style.',
                'pill_icon' => '⚡',
                'pill_label' => 'Flash Sale — Ends Soon!',
                'pill_value' => 'Up to 50% OFF',
                'cta_url' => route('products.index'),
                'cta_text' => '👦 Shop Boys',
                'emoji' => '👕',
                'badge' => 'NEW<br>IN<br>2025',
                'card_title' => 'Adventure Tee Set',
                'chips' => [
                    ['icon' => '🏅', 'label' => 'Quality', 'val' => 'Premium+', 'cls' => 'ci-yellow'],
                    ['icon' => '⚡', 'label' => 'Flash Sale', 'val' => 'Up to 50% OFF', 'cls' => 'ci-coral']
                ]
            ],
            [
                'bg_class' => 'sl-4',
                'eyebrow' => '💛 Baby & Infant',
                'headline' => 'Soft as a Hug<br><span class="line-accent">Safe as Love</span>',
                'desc' => 'Hypoallergenic, ultra-soft baby clothes tested for sensitive skin — because their comfort is our greatest priority.',
                'pill_icon' => '🌿',
                'pill_label' => '100% Organic Cotton',
                'pill_value' => 'Baby Sets ₹499+',
                'cta_url' => route('products.index'),
                'cta_text' => '🍼 Shop Baby',
                'emoji' => '🍼',
                'badge' => '100%<br>SAFE<br>ORGANIC',
                'card_title' => 'Organic Onesie Set',
                'chips' => [
                    ['icon' => '🌿', 'label' => 'Material', 'val' => '100% Organic', 'cls' => 'ci-teal'],
                    ['icon' => '🏆', 'label' => 'Certified', 'val' => 'GOTS Organic', 'cls' => 'ci-yellow']
                ]
            ]
        ];
    }
@endphp

<!-- HERO SLIDER -->
<section class="hero-wrap" id="heroWrap">
  @foreach($slidesData as $i => $slide)
    <div class="slide {{ $slide['bg_class'] }} {{ $i === 0 ? 'active' : '' }}">
      <div class="bubbles">
        <div class="bub"></div><div class="bub"></div><div class="bub"></div>
        <div class="bub"></div><div class="bub"></div><div class="bub"></div>
        <div class="bub"></div><div class="bub"></div>
      </div>
      <div class="hero-circle circle-main"></div>
      <div class="hero-circle circle-inner"></div>
      <div class="hero-circle circle-sm"></div>
      <div class="wave-bottom">
        <svg viewBox="0 0 1440 100" width="100%" height="100" preserveAspectRatio="none">
          <path fill="#FFF8F0" d="M0,60 C360,110 720,10 1080,55 C1260,78 1380,30 1440,40 L1440,100 L0,100Z"/>
        </svg>
      </div>
      <div class="slide-inner">
        <div class="slide-text">
          <div class="slide-eyebrow"><div class="eyebrow-dot"></div> {!! $slide['eyebrow'] !!}</div>
          <h1 class="slide-headline">
            {!! $slide['headline'] !!}
          </h1>
          <p class="slide-desc">{{ $slide['desc'] }}</p>
          <div class="price-pill">
            <div class="pill-icon">{{ $slide['pill_icon'] }}</div>
            <div>
              <div class="pill-label">{{ $slide['pill_label'] }}</div>
              <div class="pill-value">{{ $slide['pill_value'] }}</div>
            </div>
          </div>
          <div class="slide-cta">
            <a href="{{ $slide['cta_url'] }}" class="btn-hero btn-hero-solid">{{ $slide['cta_text'] }}</a>
          </div>
          <div class="trust-row">
            <div class="trust-badge"><span>🌿</span> 100% Cotton</div>
            <div class="trust-badge"><span>🚚</span> Free Shipping</div>
            <div class="trust-badge"><span>↩️</span> Easy Returns</div>
          </div>
        </div>
        <div class="slide-visual">
          <div class="showcase-card">
            @if(isset($slide['image']) && $slide['image'])
              <div class="showcase-card-inner" style="background-image: url('{{ $slide['image'] }}'); background-size: cover; background-position: center;"></div>
            @else
              <div class="showcase-card-inner">{{ $slide['emoji'] }}</div>
            @endif
            <div class="burst">{!! $slide['badge'] !!}</div>
            <div class="card-bottom-label">
              <small>New Arrival</small>
              <strong>{{ $slide['card_title'] }}</strong>
            </div>
          </div>
          @if(isset($slide['chips'][0]))
            <div class="chip chip-tl">
              <div class="chip-icon {{ $slide['chips'][0]['cls'] }}">{{ $slide['chips'][0]['icon'] }}</div>
              <div class="chip-label"><p>{{ $slide['chips'][0]['label'] }}</p><strong>{{ $slide['chips'][0]['val'] }}</strong></div>
            </div>
          @endif
          @if(isset($slide['chips'][1]))
            <div class="chip chip-br">
              <div class="chip-icon {{ $slide['chips'][1]['cls'] }}">{{ $slide['chips'][1]['icon'] }}</div>
              <div class="chip-label"><p>{{ $slide['chips'][1]['label'] }}</p><strong>{{ $slide['chips'][1]['val'] }}</strong></div>
            </div>
          @endif
        </div>
      </div>
    </div>
  @endforeach

  <!-- Controls -->
  <div class="slider-controls">
    <button class="ctrl-btn" id="prevBtn">&#8592;</button>
    <button class="ctrl-btn" id="nextBtn">&#8594;</button>
  </div>
  <div class="dots-wrap" id="dotsWrap">
    @foreach($slidesData as $i => $slide)
      <button class="dot {{ $i === 0 ? 'active' : '' }}" data-i="{{ $i }}"></button>
    @endforeach
  </div>
  <div class="slide-counter">
    <span class="cur" id="curNum">01</span> / <span id="totNum">{{ sprintf('%02d', count($slidesData)) }}</span>
  </div>
  <div class="progress-line" id="progressLine"></div>
</section>

<!-- FEATURE STRIP -->
<div class="feature-strip">
  <div class="feature-item">
    <div class="fi-icon">🚚</div>
    <div class="fi-text"><small>Free Shipping</small><strong>Orders ₹999+</strong></div>
  </div>
  <div class="feature-item">
    <div class="fi-icon">↩️</div>
    <div class="fi-text"><small>Easy Returns</small><strong>7-Day Policy</strong></div>
  </div>
  <div class="feature-item">
    <div class="fi-icon">🔒</div>
    <div class="fi-text"><small>Secure Payment</small><strong>100% Encrypted</strong></div>
  </div>
  <div class="feature-item">
    <div class="fi-icon">🎧</div>
    <div class="fi-text"><small>24×7 Support</small><strong>Always Here</strong></div>
  </div>
</div>

<!-- CATEGORIES -->
<section class="categories">
  <div class="sec-head">
    <div class="sec-pill">Browse by Category</div>
    <h2 class="sec-title">Shop by <span class="hi">Age & Style</span></h2>
    <p class="sec-sub">Find the perfect outfit for every kid, every occasion</p>
  </div>
  <div class="cat-grid cat-grid-4">
    <a href="{{ route('category.show', 'boys-clothing') }}" class="cat-card c2">
      <div class="cat-emoji">👦</div>
      <div class="cat-label"><small>3–12 Years</small><strong>Boys Fashion</strong></div>
    </a>
    <a href="{{ route('products.index', ['search' => 'baby']) }}" class="cat-card c3">
      <div class="cat-emoji">🍼</div>
      <div class="cat-label"><small>0–3 Years</small><strong>Baby Wear</strong></div>
    </a>
    <a href="{{ route('products.index', ['search' => 'school']) }}" class="cat-card c4">
      <div class="cat-emoji">🎒</div>
      <div class="cat-label"><small>All Ages</small><strong>School Wear</strong></div>
    </a>
    <a href="{{ route('products.index', ['search' => 'party']) }}" class="cat-card c5">
      <div class="cat-emoji">🎉</div>
      <div class="cat-label"><small>All Ages</small><strong>Party Wear</strong></div>
    </a>
  </div>
</section>

<!-- PROMO BANNERS -->
<section class="promos">
  <div class="promo-grid">
    <div class="promo p1">
      <div class="promo-bg-text">☀️</div>
      <small>Limited Time Offer</small>
      <h2>Mega Summer<br>Clearance Sale</h2>
      <p>Grab top styles at up to 60% off before they're gone. Limited stock — don't miss it!</p>
      <a href="{{ route('products.index', ['filter' => 'sale']) }}" class="promo-btn">⚡ Shop Sale Now</a>
    </div>
    <div class="promo p2">
      <div class="promo-bg-text">🎒</div>
      <small>Exclusive Collection</small>
      <h2>Back-to-School<br>Essentials</h2>
      <p>Uniforms, bags, and everyday wear — everything your child needs for a great school year.</p>
      <a href="{{ route('products.index', ['search' => 'school']) }}" class="promo-btn">📚 Explore Now</a>
    </div>
  </div>
</section>

<!-- PRODUCTS -->
<section class="products">
  <div class="sec-head">
    <div class="sec-pill">Fresh Drops</div>
    <h2 class="sec-title">Latest <span class="hi">Arrivals</span></h2>
    <p class="sec-sub">Hand-picked styles added this week</p>
  </div>
  <div class="product-grid">
    @forelse($latestProducts as $product)
        <x-product-card :product="$product" />
    @empty
        <div style="grid-column: span 4; text-align: center; padding: 40px 0;">
            <p>No products available at the moment.</p>
        </div>
    @endforelse
  </div>
</section>

<!-- NEWSLETTER -->
<div class="newsletter">
  <div class="nl-bg"></div>
  <div class="nl-bubbles">
    <div class="nl-bub"></div><div class="nl-bub"></div><div class="nl-bub"></div>
  </div>
  <div class="nl-text">
    <h2>Get <span class="wv">Early Access</span><br>to New Drops</h2>
    <p>Subscribe for exclusive discounts, style tips, and first look at new arrivals for your little ones.</p>
  </div>
  <form action="{{ route('newsletter.subscribe') }}" method="POST" class="nl-form" id="homepageNewsletterForm">
    @csrf
    <div class="nl-input-wrap">
      <input class="nl-input" type="email" name="email" placeholder="Enter your email address" required>
      <button type="submit" class="nl-btn">Subscribe 📨</button>
    </div>
    <p class="nl-note">🔒 No spam, ever. Unsubscribe anytime. We respect your privacy.</p>
  </form>
</div>

<!-- BACK TO TOP -->
<button class="back-top" id="backTop">↑</button>

@endsection

@push('scripts')
<script>
// ── HERO SLIDER ──────────────────────────────────
const slides   = document.querySelectorAll('.slide');
const dots     = document.querySelectorAll('.dot');
const curNum   = document.getElementById('curNum');
const progLine = document.getElementById('progressLine');
let cur        = 0;
let autoTimer  = null;
const DELAY    = 5000;

const pad = n => String(n + 1).padStart(2, '0');

function goTo(idx) {
  if (!slides.length) return;
  slides[cur].classList.remove('active');
  dots[cur].classList.remove('active');
  cur = (idx + slides.length) % slides.length;
  slides[cur].classList.add('active');
  dots[cur].classList.add('active');
  curNum.textContent = pad(cur);
  resetProgress();
}

function resetProgress() {
  if (!progLine) return;
  clearInterval(autoTimer);
  progLine.style.transition = 'none';
  progLine.style.width = '0%';
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      progLine.style.transition = `width ${DELAY}ms linear`;
      progLine.style.width = '100%';
    });
  });
  autoTimer = setInterval(() => goTo(cur + 1), DELAY);
}

const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');

if (nextBtn) nextBtn.addEventListener('click', () => goTo(cur + 1));
if (prevBtn) prevBtn.addEventListener('click', () => goTo(cur - 1));

dots.forEach(d => d.addEventListener('click', () => goTo(+d.dataset.i)));

document.addEventListener('keydown', e => {
  if (e.key === 'ArrowRight') goTo(cur + 1);
  if (e.key === 'ArrowLeft')  goTo(cur - 1);
});

// Swipe support
let tx = 0;
const hw = document.getElementById('heroWrap');
if (hw) {
  hw.addEventListener('touchstart', e => { tx = e.changedTouches[0].clientX; }, { passive: true });
  hw.addEventListener('touchend',   e => {
    const dx = tx - e.changedTouches[0].clientX;
    if (Math.abs(dx) > 50) goTo(dx > 0 ? cur + 1 : cur - 1);
  }, { passive: true });

  hw.addEventListener('mouseenter', () => clearInterval(autoTimer));
  hw.addEventListener('mouseleave', resetProgress);
}

if (slides.length > 0) {
  resetProgress();
}

// ── BACK TO TOP ──────────────────────────────────
const backTop = document.getElementById('backTop');
if (backTop) {
  window.addEventListener('scroll', () => {
    backTop.classList.toggle('show', window.scrollY > 300);
  });
  backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}
</script>
@endpush
