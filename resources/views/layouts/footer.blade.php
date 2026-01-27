    <!-- Footer Section Start -->
    <footer class="footer-style-1">
        <section class="section-b-space darken-layout">
            <div class="container">
                <div class="row footer-theme g-md-5 g-2">
                    <div class="col-xl-3 col-lg-5 col-md-6 sub-title">
                        <div>
                            <div class="footer-logo"><a href="{{ route('home') }}"><img alt="logo" class="img-fluid"
                                        src="{{ asset('frontassets/images/logo.png') }}">
                                </a></div>
                            <p> Discover the latest trends and enjoy seamless shopping
                                with our exclusive collections.
                            </p>

                            <ul class="contact-list">
                                <li><i class="ri-map-pin-line"></i>
                                    Multikart Demo Store, Demo Store India 345-659 </li>

                                <li><i class="ri-phone-line"></i>
                                    Call Us: 123-456-7898 </li>
                                <li><i class="ri-mail-line"></i>
                                    Email Us: Support@Multikart.com </li>
                            </ul>
                        </div>
                    </div>
                    @if(isset($footerMenus))
                        @foreach($footerMenus as $section)
                            @if($section->children->count() > 0)
                                <div class="col-xl-2 col-lg-3 col-md-4 col-md-6">
                                    <div class="sub-title">
                                        <div class="footer-title">
                                            <h4>{{ $section->name }}</h4>
                                        </div>
                                        <div class="footer-content">
                                            <ul>
                                                @foreach($section->children as $link)
                                                    <li><a href="{{ $link->url }}" class="text-content">{{ $link->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="sub-title">
                            <div class="footer-title">
                                <h4>Follow Us</h4>
                            </div>
                            <div class="footer-content">
                                <p class="mb-cls-content">Never Miss Anything From
                                    Store By Signing Up To Our Newsletter.</p>
                                <form novalidate="" class="form-inline">
                                    <div class="form-group me-sm-3 mb-2">
                                        <input type="email" class="form-control" placeholder="Enter Email Address">
                                    </div>
                                    <button class="btn btn-solid mb-2">Subscribe</button>
                                </form>
                                <div class="footer-social">
                                    <ul>
                                        <li>
                                            <a target="_blank" href="https://facebook.com/">
                                                <i class="ri-facebook-fill"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="https://twitter.com/">
                                                <i class="ri-twitter-fill"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="https://instagram.com/">
                                                <i class="ri-instagram-fill"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="https://pinterest.com/">
                                                <i class="ri-pinterest-fill"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="sub-footer dark-subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="footer-end">
                            <p><i class="ri-copyright-line"></i> 2026 Jango Kids</p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="payment-card-bottom">
                            <img alt="payment options" src="{{ asset('frontassets/images/payment.png') }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->
