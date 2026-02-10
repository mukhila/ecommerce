@extends('layouts.master')
@section('title', 'Privacy Policy | Jango Tailors')

@section('content')

    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>Privacy Policy</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Privacy Policy</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="about-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="content-text">
                        <h4 class="mb-3">1. Introduction</h4>
                        <p class="mb-4">Welcome to Jango Tailors. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our website (regardless of where you visit it from) and tell you about your privacy rights and how the law protects you.</p>

                        <h4 class="mb-3">2. Data We Collect</h4>
                        <p class="mb-4">We may collect, use, store and transfer different kinds of personal data about you which we have grouped together follows:
                            <br>- <strong>Identity Data</strong> includes first name, last name, username or similar identifier.
                            <br>- <strong>Contact Data</strong> includes billing address, delivery address, email address and telephone numbers.
                            <br>- <strong>Transaction Data</strong> includes details about payments to and from you and other details of products and services you have purchased from us.
                        </p>

                        <h4 class="mb-3">3. How We Use Your Data</h4>
                        <p class="mb-4">We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:
                            <br>- Where we need to perform the contract we are about to enter into or have entered into with you.
                            <br>- Where it is necessary for our legitimate interests (or those of a third party) and your interests and fundamental rights do not override those interests.
                            <br>- Where we need to comply with a legal or regulatory obligation.
                        </p>

                        <h4 class="mb-3">4. Data Security</h4>
                        <p class="mb-4">We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed. In addition, we limit access to your personal data to those employees, agents, contractors and other third parties who have a business need to know.</p>

                        <h4 class="mb-3">5. Your Legal Rights</h4>
                        <p class="mb-0">Under certain circumstances, you have rights under data protection laws in relation to your personal data, including the right to access, correct, erase, restrict, transfer, or object to processing of your personal data.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->

@endsection
