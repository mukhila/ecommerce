<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>JangoKids — Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('adminassets/images/favicon.ico') }}">
    <link href="{{ asset('adminassets/css/icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/app.min.css') }}" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            font-family: 'Nunito', 'Inter', sans-serif;
            background: #f4f6fa;
        }

        /* ── Left Brand Panel ─────────────────────────── */
        .jk-panel {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 46%;
            min-height: 100vh;
            padding: 48px 52px;
            background: linear-gradient(150deg, #ec8951 0%, #d4640a 55%, #a84e08 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 960px) { .jk-panel { display: flex; } }

        /* decorative blobs */
        .jk-panel::before,
        .jk-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: .18;
        }
        .jk-panel::before {
            width: 420px; height: 420px;
            background: #fff;
            top: -120px; right: -130px;
        }
        .jk-panel::after {
            width: 300px; height: 300px;
            background: #fff;
            bottom: -80px; left: -80px;
        }

        .jk-brand {
            position: relative; z-index: 1;
        }
        .jk-brand-name {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0 0 4px;
        }
        .jk-brand-name span { opacity: .75; font-weight: 400; }
        .jk-badge {
            display: inline-block;
            background: rgba(255,255,255,.2);
            border: 1px solid rgba(255,255,255,.35);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .jk-mid { position: relative; z-index: 1; }
        .jk-mid h2 {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.3;
            margin: 0 0 14px;
        }
        .jk-mid p {
            font-size: .95rem;
            opacity: .85;
            line-height: 1.7;
            margin: 0 0 32px;
        }

        .jk-stats {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        .jk-stat {
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 12px;
            padding: 14px 18px;
            min-width: 100px;
        }
        .jk-stat-num {
            font-size: 1.4rem;
            font-weight: 800;
            display: block;
        }
        .jk-stat-lbl {
            font-size: .72rem;
            opacity: .8;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .jk-foot {
            position: relative; z-index: 1;
            font-size: .8rem;
            opacity: .65;
        }

        /* ── Right Form Panel ─────────────────────────── */
        .jk-form-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            background: #f4f6fa;
        }

        .jk-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 32px rgba(0,0,0,.07);
            padding: 40px 36px;
        }

        /* mobile brand (shown only < 960px) */
        .jk-mobile-brand {
            display: block;
            font-size: 1.5rem;
            font-weight: 800;
            color: #ec8951;
            margin-bottom: 4px;
        }
        @media (min-width: 960px) { .jk-mobile-brand { display: none; } }

        .jk-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 6px;
        }
        .jk-card .sub {
            color: #6c757d;
            font-size: .88rem;
            margin: 0 0 28px;
        }

        /* inputs */
        .jk-field { margin-bottom: 20px; }
        .jk-field label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .jk-field .input-wrap { position: relative; }
        .jk-field .input-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
            pointer-events: none;
        }
        .jk-field input {
            width: 100%;
            height: 46px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 42px 0 40px;
            font-size: .9rem;
            color: #1a1a2e;
            background: #fafbfc;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .jk-field input:focus {
            border-color: #ec8951;
            box-shadow: 0 0 0 3px rgba(236,137,81,.12);
            background: #fff;
        }
        .jk-field input.is-invalid { border-color: #dc3545; }
        .jk-field .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            padding: 0;
            font-size: 17px;
            line-height: 1;
        }
        .jk-field .toggle-pw:hover { color: #ec8951; }
        .invalid-feedback { display: block; color: #dc3545; font-size: .8rem; margin-top: 4px; }

        /* remember + forgot row */
        .jk-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .jk-check { display: flex; align-items: center; gap: 7px; font-size: .85rem; color: #555; }
        .jk-check input[type=checkbox] { accent-color: #ec8951; width: 15px; height: 15px; cursor: pointer; }
        .jk-forgot { font-size: .82rem; color: #ec8951; text-decoration: none; font-weight: 600; }
        .jk-forgot:hover { text-decoration: underline; }

        /* submit button */
        .jk-btn {
            width: 100%;
            height: 48px;
            background: linear-gradient(90deg, #ec8951, #d4640a);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: .3px;
            transition: opacity .2s, transform .1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .jk-btn:hover  { opacity: .92; }
        .jk-btn:active { transform: scale(.98); }

        .jk-copy {
            text-align: center;
            margin-top: 24px;
            font-size: .78rem;
            color: #aaa;
        }

        /* alert flash */
        .jk-alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: .85rem;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .jk-alert-error  { background: #fff0f0; border: 1px solid #f5c6cb; color: #721c24; }
        .jk-alert-success { background: #f0fff4; border: 1px solid #c3e6cb; color: #155724; }
    </style>
</head>
<body>

    {{-- ── Left Brand Panel ──────────────────────────────────────────────────── --}}
    <div class="jk-panel">
        <div class="jk-brand">
            <div class="jk-brand-name">Jango<span>Kids</span></div>
            <div class="jk-badge">Admin Portal</div>
        </div>

        <div class="jk-mid">
            <h2>Your store,<br>fully in control.</h2>
            <p>
                Manage orders, products, customers, coupons,
                and analytics — all from one powerful dashboard
                built for JangoKids.
            </p>
            <div class="jk-stats">
                <div class="jk-stat">
                    <span class="jk-stat-num">Orders</span>
                    <span class="jk-stat-lbl">Real-time tracking</span>
                </div>
                <div class="jk-stat">
                    <span class="jk-stat-num">Products</span>
                    <span class="jk-stat-lbl">Full inventory</span>
                </div>
                <div class="jk-stat">
                    <span class="jk-stat-num">Reports</span>
                    <span class="jk-stat-lbl">Sales analytics</span>
                </div>
            </div>
        </div>

        <div class="jk-foot">
            &copy; {{ date('Y') }} JangoKids. All rights reserved.
        </div>
    </div>

    {{-- ── Right Form Panel ──────────────────────────────────────────────────── --}}
    <div class="jk-form-wrap">
        <div class="jk-card">

            {{-- Mobile-only brand --}}
            <div class="jk-mobile-brand">JangoKids</div>

            <h3>Welcome back</h3>
            <p class="sub">Sign in to your admin account to continue</p>

            {{-- Flash messages --}}
            @if(session('error'))
                <div class="jk-alert jk-alert-error">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="jk-alert jk-alert-success">
                    <i class="mdi mdi-check-circle-outline"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" autocomplete="on">
                @csrf

                {{-- Email --}}
                <div class="jk-field">
                    <label for="email">Email address</label>
                    <div class="input-wrap">
                        <i class="mdi mdi-email-outline"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               placeholder="admin@jangokids.com"
                               value="{{ old('email') }}"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                               autocomplete="email"
                               required
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback"><i class="mdi mdi-alert-outline"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="jk-field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="mdi mdi-lock-outline"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="••••••••"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                               autocomplete="current-password"
                               required>
                        <button type="button" class="toggle-pw" id="togglePw" title="Show / hide password">
                            <i class="mdi mdi-eye-outline" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback"><i class="mdi mdi-alert-outline"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="jk-row">
                    <label class="jk-check">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    <span class="jk-forgot" style="cursor:default;opacity:.6;font-size:.78rem;">Contact super admin</span>
                </div>

                <button type="submit" class="jk-btn">
                    <i class="mdi mdi-login"></i>
                    Sign in to Dashboard
                </button>
            </form>

            <div class="jk-copy">
                &copy; {{ date('Y') }} JangoKids Admin &mdash; Secure access only
            </div>
        </div>
    </div>

    <script src="{{ asset('adminassets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminassets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePw').addEventListener('click', function () {
            const pw  = document.getElementById('password');
            const eye = document.getElementById('eyeIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                eye.className = 'mdi mdi-eye-off-outline';
                this.title = 'Hide password';
            } else {
                pw.type = 'password';
                eye.className = 'mdi mdi-eye-outline';
                this.title = 'Show password';
            }
        });
    </script>
</body>
</html>
