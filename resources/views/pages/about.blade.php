@extends('layouts.master')
@section('title', 'About Us | Jango Tailors')

@section('content')

    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>about us</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">About Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- about section start -->
    <section class="about-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-section">
                        <img src="{{ asset('frontassets/images/about/about-us.jpg') }}" class="img-fluid blur-up lazyload" alt="About Jango Tailors">
                    </div>
                </div>
                <div class="col-sm-12">
                    <h4>Building a Legacy, One Stitch at a Time.</h4>
                    <p class="mb-4">
                        Our story begins with Mr. Muthaiya Pillai, a skilled tailor whose dedication shaped the foundation of 
                        our family’s craft. Although he had seven sons, he chose to teach tailoring only to the youngest, 
                        Mr. M. Ramesh Kumar, believing he had the passion to carry the legacy forward.
                    </p>
                    <p class="mb-4">
                        From the age of 10, Ramesh Kumar immersed himself in learning the art of tailoring. His journey took 
                        him across Bombay, Chennai, Madurai, and Dindigul, where he worked under master tailors, observing, 
                        assisting, and perfecting every detail of the trade.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- about section end -->

    <!-- Timeline section -->
    <section class="section-b-space bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2>Our Journey</h2>
                    <div class="line"></div>
                </div>
                
                <div class="col-lg-10 offset-lg-1">
                    <div class="timeline-container">
                        
                        <!-- 1986 -->
                        <div class="row align-items-center mb-5">
                            <div class="col-md-2 text-md-end text-center">
                                <h2 class="text-primary fw-bold display-4">1986</h2>
                            </div>
                            <div class="col-md-10 border-start border-3 border-primary ps-4">
                                <h3>The Beginning of Jango Tailors</h3>
                                <p>Mr. M. Ramesh Kumar founded Jango Tailors. The name “Jango” was inspired by his guru — a tailor 
                                who could neither hear nor speak, yet was admired as one of the finest craftsmen in the city. 
                                Starting with just one sewing machine and one cutting table, it was built purely on passion and dedication.</p>
                            </div>
                        </div>

                        <!-- 1990 -->
                        <div class="row align-items-center mb-5">
                            <div class="col-md-2 text-md-end text-center">
                                <h2 class="text-secondary fw-bold">1990</h2>
                            </div>
                            <div class="col-md-10 border-start border-3 border-secondary ps-4">
                                <h3>Expansion to Virudhunagar</h3>
                                <p>The workshop expanded to 5 machines and 6 team members in Virudhunagar Main Bazaar, marking the 
                                first significant step in our growth.</p>
                            </div>
                        </div>

                        <!-- 2000 -->
                        <div class="row align-items-center mb-5">
                            <div class="col-md-2 text-md-end text-center">
                                <h2 class="text-primary fw-bold">2000</h2>
                            </div>
                            <div class="col-md-10 border-start border-3 border-primary ps-4">
                                <h3>Becoming a Household Name</h3>
                                <p>We grew further to 10 machines and 15 skilled tailors, serving a growing base of loyal customers.</p>
                            </div>
                        </div>

                        <!-- 2005 -->
                        <div class="row align-items-center mb-5">
                            <div class="col-md-2 text-md-end text-center">
                                <h2 class="text-warning fw-bold">2005</h2>
                            </div>
                            <div class="col-md-10 border-start border-3 border-warning ps-4">
                                <h3>Award-Winning Craftsmanship</h3>
                                <p>Jango earned the prestigious <strong>UTKs Master of Men’s Wear Designer Award</strong>, a proud 
                                milestone for our family and a testament to our quality.</p>
                            </div>
                        </div>

                        <!-- 2025 -->
                        <div class="row align-items-center mb-5">
                            <div class="col-md-2 text-md-end text-center">
                                <h2 class="text-success fw-bold">2025</h2>
                            </div>
                            <div class="col-md-10 border-start border-3 border-success ps-4">
                                <h3>The Next Generation & Modern Vision</h3>
                                <p>
                                    <strong>Arun Kumar</strong>, Fashion Designer and son of Mr. Ramesh Kumar, joined the business to add 
                                    modern design, creative vision, and new-age fashion sensibilities.
                                </p>
                                <p>
                                    That same year, we expanded into <strong>Jango Designer Studio & Textiles</strong>, bringing together 
                                    bespoke tailoring and premium curated fabrics under one brand.
                                </p>
                            </div>
                        </div>

                        <!-- 2026 -->
                        <div class="row align-items-center mb-5">
                            <div class="col-md-2 text-md-end text-center">
                                <h2 class="theme-color fw-bold display-4">2026</h2>
                            </div>
                            <div class="col-md-10 border-start border-3 border-theme ps-4">
                                <h3>Digital Transformation & 40th Year</h3>
                                <p>
                                    A new strength joined our journey — <strong>Vanitha</strong>, an MBA in HR, stepped into the business 
                                    to lead our online division, building Jango’s digital presence with structure, strategy, and professionalism.
                                </p>
                                <p>
                                    This year, Jango proudly enters its <strong>40th year</strong>. Four decades of trust, craftsmanship, 
                                    and transformation — from a single machine in 1986 to a designer studio, textiles, and now a 
                                    full online brand.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Timeline section end -->

    <!-- Jango Kids Section -->
    <section class="section-b-space bg-white">
        <div class="container">
            <div class="row align-items-center">
                 <div class="col-md-6 mb-4 mb-md-0">
                    <div class="text-center p-5 bg-light rounded-3">
                        <i class="ri-heart-3-line display-1 text-danger mb-3"></i>
                        <h3 class="fw-bold">Jango Kids</h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Celebrating 40 Years with a New Chapter</h3>
                    <p class="lead">As we celebrate 40 years, we are excited to introduce our newest chapter: <strong>Jango Kids</strong>.</p>
                    <p>A space created to bring style, comfort, and craftsmanship to the next generation.</p>
                    <p class="mt-4 font-italic">
                        "From 1986 to 2026 and beyond — our journey continues with trust, tradition, and timeless design."
                    </p>
                </div>
            </div>
        </div>
    </section>
    
@endsection
