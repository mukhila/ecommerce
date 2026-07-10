    <!-- Footer Section Start -->
    <footer class="footer-style-1">
        <section class="section-b-space darken-layout">
            <div class="container">

                @php
                    /*
                     * Collect every URL already rendered in the DB Information column
                     * so Quick Links can skip any duplicates.
                     */
                    $skipSections  = ['my account', 'my accounts', 'my-account'];
                    $dbFooterUrls  = [];

                    if (isset($footerMenus)) {
                        foreach ($footerMenus as $_s) {
                            if ($_s->children->count() > 0
                                && !in_array(strtolower(trim($_s->name)), $skipSections)) {
                                foreach ($_s->children as $_l) {
                                    $dbFooterUrls[] = rtrim($_l->url, '/');
                                }
                            }
                        }
                    }

                    /*
                     * Quick Links pool — only shown when not already in $dbFooterUrls.
                     */
                    $quickLinks = [
                        ['label' => 'Track Order',        'url' => route('order.track')],
                        ['label' => 'Size Guide',         'url' => route('size-guide')],
                        ['label' => 'My Account',         'url' => route('login')],
                        ['label' => 'Contact Us',         'url' => route('contact')],
                        ['label' => 'FAQs',               'url' => route('faqs')],
                        ['label' => 'Shipping Policy',    'url' => route('shipping-policy')],
                        ['label' => 'Return Policy',      'url' => route('return-policy')],
                        ['label' => 'Privacy Policy',     'url' => route('privacy-policy')],
                        ['label' => 'Terms & Conditions', 'url' => route('terms-and-conditions')],
                    ];

                    $visibleQuickLinks = array_values(array_filter(
                        $quickLinks,
                        fn($item) => !in_array(rtrim($item['url'], '/'), $dbFooterUrls)
                    ));
                @endphp

                <div class="row footer-theme g-md-5 g-4">

                    {{-- ── COL 1 · Logo & Contact ─────────────────────── --}}
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 sub-title">
                        <div class="footer-logo mb-3">
                            <a href="{{ route('home') }}">
                                <img alt="Jango Kidswear" class="img-fluid"
                                     src="{{ asset('frontassets/images/logo.png') }}">
                            </a>
                        </div>
                        <p>Discover the latest trends and enjoy seamless shopping with our exclusive kids' collections.</p>
                        <ul class="contact-list">
                            <li><i class="ri-map-pin-line"></i> Jango Kidswear, India</li>
                            <li><i class="ri-phone-line"></i> +91 98765 43210 &nbsp;|&nbsp; +91 98765 43211</li>
                            <li><i class="ri-mail-line"></i> support@jangokids.com</li>
                        </ul>
                    </div>

                    {{-- ── COL 2 · Information (DB-driven) ────────────── --}}
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 sub-title">
                        <div class="footer-title">
                            <h4>Information</h4>
                        </div>
                        <div class="footer-content">
                            @if(isset($footerMenus) && $footerMenus->count() > 0)
                                @foreach($footerMenus as $section)
                                    @if($section->children->count() > 0
                                        && !in_array(strtolower(trim($section->name)), $skipSections))
                                        {{-- Show section name only if there are multiple sections --}}
                                        @if($footerMenus->filter(fn($s) =>
                                                $s->children->count() > 0
                                                && !in_array(strtolower(trim($s->name)), $skipSections)
                                            )->count() > 1)
                                            <p class="mb-1 mt-2" style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;opacity:.6;">
                                                {{ $section->name }}
                                            </p>
                                        @endif
                                        <ul>
                                            @foreach($section->children as $link)
                                                <li>
                                                    <a href="{{ $link->url }}" class="text-content">
                                                        {{ $link->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach
                            @else
                                {{-- Fallback when no admin menus are configured --}}
                                <ul>
                                    <li><a href="{{ route('about') }}" class="text-content">About Us</a></li>
                                    <li><a href="{{ route('contact') }}" class="text-content">Contact Us</a></li>
                                    <li><a href="{{ route('faqs') }}" class="text-content">FAQs</a></li>
                                    <li><a href="{{ route('privacy-policy') }}" class="text-content">Privacy Policy</a></li>
                                    <li><a href="{{ route('terms-and-conditions') }}" class="text-content">Terms &amp; Conditions</a></li>
                                </ul>
                            @endif
                        </div>
                    </div>

                    {{-- ── COL 3 · Quick Links ─────────────────────────── --}}
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 sub-title">
                        <div class="footer-title">
                            <h4>Quick Links</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                @foreach($visibleQuickLinks as $ql)
                                    <li><a href="{{ $ql['url'] }}" class="text-content">{{ $ql['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- ── COL 4 · Newsletter & Social ─────────────────── --}}
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 sub-title">
                        <div class="footer-title">
                            <h4>Stay Connected</h4>
                        </div>
                        <div class="footer-content">
                            <p class="mb-cls-content">Never miss a new arrival or exclusive offer — subscribe to our newsletter.</p>
                            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="form-inline mb-3" id="newsletterForm">
                                @csrf
                                <div class="form-group me-sm-2 mb-2">
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                                </div>
                                <button type="submit" class="btn btn-solid mb-2">Subscribe</button>
                            </form>
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
                            <div class="footer-social">
                                <ul>
                                    @foreach($socialPlatforms as $key => $platform)
                                        @if(!empty($socialLinks[$key]))
                                            <li>
                                                <a target="_blank" href="{{ $socialLinks[$key] }}" aria-label="{{ $platform['label'] }}">
                                                    <i class="{{ $platform['icon'] }}"></i>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                    @if(empty(array_filter($socialLinks ?? [])))
                                        {{-- Fallback placeholders until admin configures social links --}}
                                        <li><a target="_blank" href="#" aria-label="Facebook"><i class="ri-facebook-fill"></i></a></li>
                                        <li><a target="_blank" href="#" aria-label="Instagram"><i class="ri-instagram-fill"></i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>{{-- end .row --}}
            </div>
        </section>

        <div class="sub-footer dark-subfooter">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="footer-end">
                            <p><i class="ri-copyright-line"></i> {{ date('Y') }} Jango Kidswear. All rights reserved.</p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="payment-card-bottom">
                            <img alt="Accepted payment methods"
                                 src="{{ asset('frontassets/images/payment.png') }}"
                                 class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->
