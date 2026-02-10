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

    <!-- Hero Section -->
    <section class="about-hero-section position-relative overflow-hidden">
        <div class="about-hero-bg" style="background-image: url('{{ asset('frontassets/images/about/about-us.jpg') }}');"></div>
        <div class="about-hero-overlay"></div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white py-5">
                    <h1 class="display-4 fw-bold mb-3">Building a Legacy,<br>One Stitch at a Time</h1>
                    <p class="lead mb-0">Four decades of trust, craftsmanship, and transformation — from a single machine in 1986 to a full designer studio & online brand.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Our Story Section -->
    <section class="section-b-space pt-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="text-uppercase text-muted fw-semibold letter-spacing-2" style="font-size: 0.85rem; letter-spacing: 2px;">Our Story</span>
                    <h2 class="fw-bold mt-2 mb-4">A Craft Passed Down Through Generations</h2>
                    <p class="text-muted mb-3">
                        Our story begins with <strong>Mr. Muthaiya Pillai</strong>, a skilled tailor whose dedication shaped the foundation
                        of our family's craft. Although he had seven sons, he chose to teach tailoring only to the youngest,
                        <strong>Mr. M. Ramesh Kumar</strong>, believing he had the passion to carry the legacy forward.
                    </p>
                    <p class="text-muted mb-0">
                        From the age of 10, Ramesh Kumar immersed himself in learning the art of tailoring. His journey took
                        him across Bombay, Chennai, Madurai, and Dindigul, where he worked under master tailors, observing,
                        assisting, and perfecting every detail of the trade.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="about-story-image position-relative">
                        <img src="{{ asset('frontassets/images/about/about-us.jpg') }}" class="img-fluid rounded-3 shadow" alt="About Jango Tailors">
                        <div class="about-experience-badge">
                            <span class="display-5 fw-bold">40</span>
                            <span class="d-block small">Years of<br>Excellence</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Our Story Section End -->

    <!-- Stats Section -->
    <section class="about-stats-section py-5">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-lg-3">
                    <div class="about-stat-item">
                        <div class="about-stat-number">40+</div>
                        <div class="about-stat-label">Years of Experience</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="about-stat-item">
                        <div class="about-stat-number">15+</div>
                        <div class="about-stat-label">Skilled Tailors</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="about-stat-item">
                        <div class="about-stat-number">10+</div>
                        <div class="about-stat-label">Machines</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="about-stat-item">
                        <div class="about-stat-number">1000+</div>
                        <div class="about-stat-label">Happy Customers</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Stats Section End -->

    <!-- Timeline Section -->
    <section class="section-b-space bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <span class="text-uppercase text-muted fw-semibold" style="font-size: 0.85rem; letter-spacing: 2px;">Our Journey</span>
                    <h2 class="fw-bold mt-2">Milestones That Define Us</h2>
                </div>
            </div>

            <div class="about-timeline">
                <!-- 1986 -->
                <div class="about-timeline-item">
                    <div class="about-timeline-dot"></div>
                    <div class="about-timeline-content">
                        <span class="about-timeline-year">1986</span>
                        <h4 class="fw-bold mt-2 mb-2">The Beginning of Jango Tailors</h4>
                        <p class="text-muted mb-0">Mr. M. Ramesh Kumar founded Jango Tailors. The name "Jango" was inspired by his guru — a tailor
                        who could neither hear nor speak, yet was admired as one of the finest craftsmen in the city.
                        Starting with just one sewing machine and one cutting table, it was built purely on passion and dedication.</p>
                    </div>
                </div>

                <!-- 1990 -->
                <div class="about-timeline-item">
                    <div class="about-timeline-dot"></div>
                    <div class="about-timeline-content">
                        <span class="about-timeline-year">1990</span>
                        <h4 class="fw-bold mt-2 mb-2">Expansion to Virudhunagar</h4>
                        <p class="text-muted mb-0">The workshop expanded to 5 machines and 6 team members in Virudhunagar Main Bazaar, marking the
                        first significant step in our growth.</p>
                    </div>
                </div>

                <!-- 2000 -->
                <div class="about-timeline-item">
                    <div class="about-timeline-dot"></div>
                    <div class="about-timeline-content">
                        <span class="about-timeline-year">2000</span>
                        <h4 class="fw-bold mt-2 mb-2">Becoming a Household Name</h4>
                        <p class="text-muted mb-0">We grew further to 10 machines and 15 skilled tailors, serving a growing base of loyal customers.</p>
                    </div>
                </div>

                <!-- 2005 -->
                <div class="about-timeline-item">
                    <div class="about-timeline-dot"></div>
                    <div class="about-timeline-content">
                        <span class="about-timeline-year">2005</span>
                        <h4 class="fw-bold mt-2 mb-2">Award-Winning Craftsmanship</h4>
                        <p class="text-muted mb-0">Jango earned the prestigious <strong>UTKs Master of Men's Wear Designer Award</strong>, a proud
                        milestone for our family and a testament to our quality.</p>
                    </div>
                </div>

                <!-- 2025 -->
                <div class="about-timeline-item">
                    <div class="about-timeline-dot"></div>
                    <div class="about-timeline-content">
                        <span class="about-timeline-year">2025</span>
                        <h4 class="fw-bold mt-2 mb-2">The Next Generation & Modern Vision</h4>
                        <p class="text-muted mb-2"><strong>Arun Kumar</strong>, Fashion Designer and son of Mr. Ramesh Kumar, joined the business to add
                        modern design, creative vision, and new-age fashion sensibilities.</p>
                        <p class="text-muted mb-0">That same year, we expanded into <strong>Jango Designer Studio & Textiles</strong>, bringing together
                        bespoke tailoring and premium curated fabrics under one brand.</p>
                    </div>
                </div>

                <!-- 2026 -->
                <div class="about-timeline-item">
                    <div class="about-timeline-dot"></div>
                    <div class="about-timeline-content">
                        <span class="about-timeline-year">2026</span>
                        <h4 class="fw-bold mt-2 mb-2">Digital Transformation & 40th Year</h4>
                        <p class="text-muted mb-2"><strong>Vanitha</strong>, an MBA in HR, stepped into the business
                        to lead our online division, building Jango's digital presence with structure, strategy, and professionalism.</p>
                        <p class="text-muted mb-0">This year, Jango proudly enters its <strong>40th year</strong>. Four decades of trust, craftsmanship,
                        and transformation — from a single machine in 1986 to a designer studio, textiles, and now a
                        full online brand.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Timeline Section End -->

    <!-- Team / Values Section -->
    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <span class="text-uppercase text-muted fw-semibold" style="font-size: 0.85rem; letter-spacing: 2px;">Our Family</span>
                    <h2 class="fw-bold mt-2">The People Behind Jango</h2>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="about-team-card text-center p-4">
                        <div class="about-team-icon mb-3">
                            <i class="ri-scissors-line"></i>
                        </div>
                        <h5 class="fw-bold">Mr. M. Ramesh Kumar</h5>
                        <span class="text-muted d-block mb-3">Founder & Master Tailor</span>
                        <p class="text-muted small mb-0">With over 40 years of experience, he is the heart and soul of Jango. His eye for precision and dedication to quality craftsmanship built the brand from the ground up.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="about-team-card text-center p-4">
                        <div class="about-team-icon mb-3">
                            <i class="ri-palette-line"></i>
                        </div>
                        <h5 class="fw-bold">Arun Kumar</h5>
                        <span class="text-muted d-block mb-3">Fashion Designer</span>
                        <p class="text-muted small mb-0">Bringing modern design sensibilities and creative vision, Arun bridges the gap between traditional craftsmanship and contemporary fashion.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="about-team-card text-center p-4">
                        <div class="about-team-icon mb-3">
                            <i class="ri-global-line"></i>
                        </div>
                        <h5 class="fw-bold">Vanitha</h5>
                        <span class="text-muted d-block mb-3">Head of Online Division</span>
                        <p class="text-muted small mb-0">With an MBA in HR, Vanitha leads Jango's digital transformation, building the online presence with strategy and professionalism.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Team Section End -->

    <!-- Jango Kids Section -->
    <section class="about-kids-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="about-kids-visual text-center p-5 rounded-3">
                        <i class="ri-heart-3-line display-1 mb-3"></i>
                        <h3 class="fw-bold text-white">Jango Kids</h3>
                        <p class="text-white-50 mb-0">Style for the next generation</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <span class="text-uppercase text-muted fw-semibold" style="font-size: 0.85rem; letter-spacing: 2px;">New Chapter</span>
                    <h3 class="fw-bold mt-2 mb-3">Celebrating 40 Years with Jango Kids</h3>
                    <p class="lead text-muted">As we celebrate 40 years, we are excited to introduce our newest chapter: <strong>Jango Kids</strong>.</p>
                    <p class="text-muted">A space created to bring style, comfort, and craftsmanship to the next generation.</p>
                    <blockquote class="about-blockquote mt-4 ps-4">
                        <p class="fst-italic text-muted mb-0">"From 1986 to 2026 and beyond — our journey continues with trust, tradition, and timeless design."</p>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>
    <!-- Jango Kids Section End -->

    <!-- CTA Section -->
    <section class="about-cta-section py-5 text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <h2 class="fw-bold mb-3">Ready to Experience the Jango Difference?</h2>
                    <p class="text-muted mb-4">Whether you're looking for bespoke tailoring or premium fabrics, we're here to serve you.</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('contact') }}" class="btn btn-solid">Contact Us</a>
                        <a href="{{ route('home') }}" class="btn btn-outline">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- CTA Section End -->

@endsection
