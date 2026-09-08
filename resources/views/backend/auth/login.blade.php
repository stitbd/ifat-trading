<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') || Login</title>

    @php
        $company = \App\Models\Application::first();
        $companyLogo = $company ? $company->logo : '';
        $companyFavIcon = $company ? $company->fav_icon : '';
        $companyName = $company ? $company->company_name : 'ST TYRE ERP';
    @endphp

    <link rel="icon" href="{{ asset('image/application/' . $companyFavIcon) }}" type="image/png" />

    <!--begin::Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!--end::Fonts-->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />

    <style>
        :root {
            --brand-primary: #4361ee;
            --brand-primary-dark: #3651d4;
            --brand-success: #198754;
            --text-dark: #1e1e2d;
            --text-muted: #7e8299;
            --border-light: #eef0f2;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        html,
        body {
            height: 100%;
        }

        body {
            background: #f5f6fa;
        }

        /*
        |--------------------------------------------------------------------------
        | Page shell
        |--------------------------------------------------------------------------
        */
        .auth-shell {
            min-height: 100vh;
            display: flex;
        }

        /*
        |--------------------------------------------------------------------------
        | Left brand / aside panel — mirrors admin sidebar dark navy + brand blue
        |--------------------------------------------------------------------------
        */
        .auth-aside {
            width: 46%;
            background: linear-gradient(160deg, #1b2559 0%, var(--brand-primary) 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 48px;
            color: #fff;
            overflow: hidden;
        }

        .auth-aside::before {
            content: "";
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            top: -140px;
            right: -140px;
        }

        .auth-aside::after {
            content: "";
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            bottom: -120px;
            left: -100px;
        }

        .auth-aside .brand-logo {
            position: relative;
            z-index: 2;
            margin-bottom: 40px;
        }

        .auth-aside .brand-logo img {
            max-height: 70px;
            filter: brightness(0) invert(1);
        }

        .auth-aside .brand-logo .brand-fallback {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .auth-aside h1 {
            position: relative;
            z-index: 2;
            font-size: 30px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 16px;
        }

        .auth-aside p {
            position: relative;
            z-index: 2;
            font-size: 15px;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            max-width: 380px;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .auth-feature-list {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            max-width: 360px;
        }

        .auth-feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13.5px;
            font-weight: 500;
        }

        .auth-feature-item i {
            font-size: 16px;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Right form panel
        |--------------------------------------------------------------------------
        */
        .auth-form-side {
            width: 54%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border: 1px solid var(--border-light);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(30, 30, 45, 0.05);
            padding: 44px 40px;
        }

        .auth-card .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: #eaf0ff;
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .auth-card h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .auth-card .auth-subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
            margin-bottom: 32px;
        }

        .form-label-sm {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            display: block;
        }

        .auth-input-group {
            position: relative;
            margin-bottom: 18px;
        }

        .auth-input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa0ac;
            font-size: 15px;
        }

        .auth-input-group .form-control {
            border: 1px solid #dfe2e8;
            border-radius: 8px;
            padding: 11px 14px 11px 40px;
            font-size: 14px;
            background: #fff;
            box-shadow: none;
        }

        .auth-input-group .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .auth-input-group .form-control.is-invalid {
            border-color: #f1416c;
        }

        .auth-input-group .toggle-password {
            position: absolute;
            right: 14px;
            left: auto;
            cursor: pointer;
        }

        .invalid-feedback-text {
            color: #f1416c;
            font-size: 12.5px;
            margin-top: 4px;
            display: block;
        }

        .btn-auth-submit {
            width: 100%;
            background: var(--brand-primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 700;
            font-size: 14.5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s ease;
        }

        .btn-auth-submit:hover {
            background: var(--brand-primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .auth-footer-note {
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 28px;
        }

        .auth-copyright {
            text-align: center;
            font-size: 12px;
            color: #b5b8c2;
            margin-top: 20px;
        }

        @media (max-width: 991px) {
            .auth-aside {
                display: none;
            }

            .auth-form-side {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="auth-shell">

        <!--begin::Aside-->
        <div class="auth-aside">
            <div class="brand-logo">
                @if ($companyLogo)
                    <img src="{{ asset('image/application/' . $companyLogo) }}" alt="{{ $companyName }}" />
                @else
                    <div class="brand-fallback">{{ $companyName }}</div>
                @endif
            </div>
            <h1>Secure Admin Access</h1>
            <p>Manage sales, purchases, inventory and requisitions from one powerful dashboard built for your team.</p>

            <div class="auth-feature-list">
                <div class="auth-feature-item">
                    <i class="bi bi-graph-up-arrow"></i>
                    Real-time sales &amp; purchase overview
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-box-seam"></i>
                    Live inventory &amp; stock aging insights
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-shield-lock"></i>
                    Role-based, secure access control
                </div>
            </div>
        </div>
        <!--end::Aside-->

        <!--begin::Form side-->
        <div class="auth-form-side">
            <div class="auth-card">
                <div class="icon-box">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h1>Sign In</h1>
                <div class="auth-subtitle">Enter your credentials to access the dashboard</div>

                <form class="w-100" action="{{ route('login') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label-sm">Email Address</label>
                        <div class="auth-input-group">
                            <i class="bi bi-envelope"></i>
                            <input type="text" placeholder="you@company.com" name="email" autocomplete="off"
                                value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" />
                        </div>
                        @error('email')
                            <span class="invalid-feedback-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label-sm">Password</label>
                        <div class="auth-input-group">
                            <i class="bi bi-lock"></i>
                            <input type="password" placeholder="••••••••" name="password" id="passwordInput"
                                autocomplete="off" class="form-control @error('password') is-invalid @enderror" />
                            <i class="bi bi-eye toggle-password" id="togglePassword"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mb-4">
                        {{-- <a href="#" class="fs-13" style="color:var(--brand-primary); font-weight:600; text-decoration:none;">Forgot password?</a> --}}
                    </div>

                    <button type="submit" class="btn-auth-submit">
                        <span class="indicator-label">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </span>
                    </button>

                    <div class="auth-footer-note">
                        Welcome back! Please log in to manage your dashboard efficiently.
                    </div>
                </form>

                <div class="auth-copyright">
                    © {{ date('Y') }} {{ $companyName }}. All rights reserved.
                </div>
            </div>
        </div>
        <!--end::Form side-->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const pwdInput = document.getElementById('passwordInput');
        if (toggleBtn && pwdInput) {
            toggleBtn.addEventListener('click', function() {
                const isPassword = pwdInput.getAttribute('type') === 'password';
                pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        }
    </script>
</body>

</html>
