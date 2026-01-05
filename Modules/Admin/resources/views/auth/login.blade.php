<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('adminassets/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
     <link href="{{ asset('adminassets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('adminassets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body class="bg-light-subtle">
     <div class="account-page">
            <div class="container-fluid p-0">
                <div class="row g-0 px-3 py-3 vh-100">

                       <div class="col-xl-6 col-lg-6 col-md-6 d-md-block d-none body-color justify-content-center align-content-center rounded-4">
                        <div class="">
                            <div class="swiper testi-swiper auth-user-review">
                                <div class="swiper-wrapper">

                                    <div class="swiper-slide">
                                        <div class="carousel-images mb-5">
                                            <img src="assets/images/auth/login_first.svg" alt="" class="img-fluid">
                                        </div>
                                        <p class="prelead mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#ffffff" d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621c.537-.278 1.24-.375 1.929-.311c1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5a3.87 3.87 0 0 1-2.748-1.179m10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621c.537-.278 1.24-.375 1.929-.311c1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5a3.87 3.87 0 0 1-2.748-1.179"/></svg> 
                                                Venix made it incredibly easy to set up our internal tools. The UI is clean, and the components are flexible and well-documented.
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#ffffff" d="M19.417 6.679C20.447 7.773 21 9 21 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.248-5.621c-.537.278-1.24.375-1.93.311c-1.804-.167-3.226-1.648-3.226-3.489a3.5 3.5 0 0 1 3.5-3.5c1.073 0 2.1.49 2.748 1.179m-10 0C10.447 7.773 11 9 11 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.247-5.621c-.537.278-1.24.375-1.929.311C4.591 12.323 3.17 10.842 3.17 9a3.5 3.5 0 0 1 3.5-3.5c1.073 0 2.1.49 2.748 1.179"/></svg>
                                        </p>
                                     
                                    </div>
                                    
                                    <div class="swiper-slide">
                                        <div class="carousel-images mb-5">
                                            <img src="assets/images/auth/login_second.svg" alt="" class="img-fluid">
                                        </div>
                                        <p class="prelead mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#ffffff" d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621c.537-.278 1.24-.375 1.929-.311c1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5a3.87 3.87 0 0 1-2.748-1.179m10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621c.537-.278 1.24-.375 1.929-.311c1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5a3.87 3.87 0 0 1-2.748-1.179"/></svg> 
                                                We built our dashboard 2x faster using Venix. The modular structure and reusable widgets saved us hours of work.
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#ffffff" d="M19.417 6.679C20.447 7.773 21 9 21 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.248-5.621c-.537.278-1.24.375-1.93.311c-1.804-.167-3.226-1.648-3.226-3.489a3.5 3.5 0 0 1 3.5-3.5c1.073 0 2.1.49 2.748 1.179m-10 0C10.447 7.773 11 9 11 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.247-5.621c-.537.278-1.24.375-1.929.311C4.591 12.323 3.17 10.842 3.17 9a3.5 3.5 0 0 1 3.5-3.5c1.073 0 2.1.49 2.748 1.179"/></svg>
                                        </p>
                                       
                                    </div>

                                    <div class="swiper-slide">
                                        <div class="carousel-images mb-5">
                                            <img src="assets/images/auth/login_third.svg" alt="" class="img-fluid">
                                        </div>
                                        <p class="prelead mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#ffffff" d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621c.537-.278 1.24-.375 1.929-.311c1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5a3.87 3.87 0 0 1-2.748-1.179m10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621c.537-.278 1.24-.375 1.929-.311c1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5a3.87 3.87 0 0 1-2.748-1.179"/></svg> 
                                            I highly recommend Venix for any developer looking to build scalable admin panels. Everything just works.
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#ffffff" d="M19.417 6.679C20.447 7.773 21 9 21 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.248-5.621c-.537.278-1.24.375-1.93.311c-1.804-.167-3.226-1.648-3.226-3.489a3.5 3.5 0 0 1 3.5-3.5c1.073 0 2.1.49 2.748 1.179m-10 0C10.447 7.773 11 9 11 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.247-5.621c-.537.278-1.24.375-1.929.311C4.591 12.323 3.17 10.842 3.17 9a3.5 3.5 0 0 1 3.5-3.5c1.073 0 2.1.49 2.748 1.179"/></svg>
                                        </p>
                                    
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>




                  <div class="col-xl-5 col-md-5 col-xxl-5 justify-content-center align-content-center mx-auto">
                    <div class="row">
                            <div class="col-xl-8 mx-auto">

                                <div class="mb-3 p-0 text-start">
                                    <div class="auth-brand">
                                        <a href="#" class="logo logo-light">
                                            <span class="logo-sm">
                                                <img src="{{ asset('adminassets/images/logo-sm.png') }}" height="40">
                                            </span>
                                        </a>
                                        <a href="index.html" class="logo logo-dark">
                                            <span class="logo-sm">
                                                <img src="{{ asset('adminassets/images/logo-sm.png') }}" alt="" height="40">
                                            </span>
                                        </a>
                                    </div>
                                </div>
								
								  <div class="auth-title-section mb-3 text-start">
                                    <h4 class="text-dark fw-medium mb-2">Welcome Admin</h4>
                                    <p class="text-muted fs-14 mb-0">Please enter your detail</p>
                                </div>
        
                           <div class="card mb-0 shadow-none border">
                                    <div class="card-body p-lg-4">
                                        <div class="mb-0">
                                <form class="form-horizontal" action="{{ route('admin.login.submit') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" required>
                                            <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <a href="#" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>© <script>document.write(new Date().getFullYear())</script> Admin. Crafted with <i class="mdi mdi-heart text-danger"></i> by Admin</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
    <!-- JAVASCRIPT -->
    <script src="{{ asset('adminassets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminassets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminassets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('adminassets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('adminassets/libs/node-waves/waves.min.js') }}"></script>
    
    <!-- App js -->
    <script src="{{ asset('adminassets/js/app.js') }}"></script>
    <script>
        document.getElementById('password-addon').addEventListener('click', function () {
            var passwordInput = document.querySelector('input[name="password"]');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });
    </script>
</body>
</html>
