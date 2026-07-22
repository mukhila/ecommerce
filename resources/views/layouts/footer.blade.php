<!-- footer start -->
<footer>
  <div class="footer-top">
    <!-- Brand Info -->
    <div class="ft-brand">
      <div class="brand-logo">
        <span class="j">J</span><span class="an">an</span><span class="go">go</span><span class="k">K</span><span class="ids">ids</span>
      </div>
      <p>Premium kids fashion combining comfort, style, and affordability. Dressed for every adventure from 0 to 12 years.</p>
      
      @php
          $socialLinks = isset($companySetting) && $companySetting
              ? ($companySetting->social_links ?? [])
              : [];
          $socialPlatforms = [
              'facebook'  => ['icon' => 'ri-facebook-fill',  'label' => 'Facebook'],
              'twitter'   => ['icon' => 'ri-twitter-fill',   'label' => 'Twitter'],
              'instagram' => ['icon' => 'ri-instagram-fill', 'label' => 'Instagram'],
              'pinterest' => ['icon' => 'ri-pinterest-fill', 'label' => 'Pinterest'],
              'youtube'   => ['icon' => 'ri-youtube-fill',   'label' => 'YouTube'],
          ];
      @endphp
      <div class="ft-socials">
        @foreach($socialPlatforms as $key => $platform)
            @if(!empty($socialLinks[$key]))
                <a target="_blank" href="{{ $socialLinks[$key] }}" class="soc-btn" aria-label="{{ $platform['label'] }}">
                    <i class="{{ $platform['icon'] }}"></i>
                </a>
            @endif
        @endforeach
        @if(empty(array_filter($socialLinks ?? [])))
            <a target="_blank" href="#" class="soc-btn" aria-label="Facebook"><i class="ri-facebook-fill"></i></a>
            <a target="_blank" href="#" class="soc-btn" aria-label="Instagram"><i class="ri-instagram-fill"></i></a>
            <a target="_blank" href="#" class="soc-btn" aria-label="Twitter"><i class="ri-twitter-fill"></i></a>
        @endif
      </div>
    </div>

    <!-- Shop Column -->
    <!--div class="ft-col">
      <h4>Shop</h4>
      <ul>
      </ul>
    </div-->

    <!-- Help Column -->
    <div class="ft-col">
      <h4>Help</h4>
      <ul>
        <li><a href="{{ route('size-guide') }}">Size Guide</a></li>
        <li><a href="{{ route('order.track') }}">Track Order</a></li>
        <li><a href="{{ route('return-policy') }}">Returns & Exchange</a></li>
        <li><a href="{{ route('faqs') }}">FAQ</a></li>
        <li><a href="{{ auth()->check() ? route('support.index') : route('support.create') }}">Support</a></li>
        <li><a href="{{ route('contact') }}">Contact Us</a></li>
      </ul>
    </div>

    <!-- Company Column -->
    <div class="ft-col">
      <h4>Company</h4>
      <ul>
        <li><a href="{{ route('about') }}">About Us</a></li>
        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
        <li><a href="{{ route('terms-and-conditions') }}">Terms & Conditions</a></li>
        <li><a href="{{ route('shipping-policy') }}">Shipping Policy</a></li>
        <!--li><a href="{{ route('sitemap') }}">Sitemap</a></li-->
      </ul>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <span>© {{ date('Y') }} JangoKids. All rights reserved.</span>
    <div class="payment-icons">
      <span class="pay-badge">UPI</span>
      <span class="pay-badge">Visa</span>
      <span class="pay-badge">Mastercard</span>
      <span class="pay-badge">COD</span>
    </div>
    <div class="footer-links">
      <a href="{{ route('privacy-policy') }}">Privacy</a>
      <a href="{{ route('terms-and-conditions') }}">Terms</a>
    </div>
  </div>
</footer>
<!-- footer end -->
