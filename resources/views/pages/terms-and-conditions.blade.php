@extends('layouts.master')
@section('title', 'Terms & Conditions | Jango Tailors')

@section('content')

    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>Terms & Conditions</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Terms & Conditions</li>
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
                        <h4 class="mb-3">1. Terms of Use</h4>
                        <p class="mb-4">By accessing this website, we assume you accept these terms and conditions. Do not continue to use Jango Tailors if you do not agree to take all of the terms and conditions stated on this page.</p>

                        <h4 class="mb-3">2. Products & Services</h4>
                        <p class="mb-4">We reserve the right to modify or discontinue any product or service without notice at any time. We have made every effort to display as accurately as possible the colors and images of our products that appear at the store. We cannot guarantee that your computer monitor's display of any color will be accurate.</p>

                        <h4 class="mb-3">3. Pricing Payment</h4>
                        <p class="mb-4">Prices for our products are subject to change without notice. We provide various payment methods including Credit Card, Debit Card, Net Banking, and UPI. All payments must be made in full before the order is processed.</p>

                        <h4 class="mb-3">4. Customization & Alterations</h4>
                        <p class="mb-4">Custom-tailored items are made based on the measurements provided by you. We are not responsible for any fitting issues if incorrect measurements were provided. Minor alterations may be offered at our discretion.</p>

                        <h4 class="mb-3">5. Intellectual Property</h4>
                        <p class="mb-4">Unless otherwise stated, Jango Tailors and/or its licensors own the intellectual property rights for all material on Jango Tailors. All intellectual property rights are reserved. You may access this for your own personal use subjected to restrictions set in these terms and conditions.</p>

                        <h4 class="mb-3">6. Limitation of Liability</h4>
                        <p class="mb-0">In no event shall Jango Tailors, nor any of its officers, directors and employees, be held liable for anything arising out of or in any way connected with your use of this website. Jango Tailors, including its officers, directors and employees shall not be held liable for any indirect, consequential or special liability arising out of or in any way related to your use of this website.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->

@endsection
