<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Tanikuy Inventory & EOQ</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Assets from Spark Admin -->
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

    <style>
        body {
            background-color: var(--brand-forest-dark);
            color: #FFFFFF;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        .login-bg-shape {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }

        .login-bg-shape-1 {
            width: 450px;
            height: 450px;
            top: -100px;
            left: -100px;
            background: radial-gradient(circle, rgba(180, 241, 5, 0.15) 0%, rgba(5, 28, 18, 0) 70%);
            filter: blur(60px);
        }

        .login-bg-shape-2 {
            width: 550px;
            height: 550px;
            bottom: -150px;
            right: -150px;
            background: radial-gradient(circle, rgba(7, 47, 31, 0.6) 0%, rgba(5, 28, 18, 0) 70%);
            filter: blur(70px);
        }

        .login-card-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 1.5rem;
        }

        .login-card-custom {
            background: rgba(7, 47, 31, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-dark-green);
            border-radius: var(--radius-xxl);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
            padding: 2.5rem 2.25rem;
        }

        .login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 1.75rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 0.5rem;
        }

        .login-brand i {
            color: var(--brand-lime);
            font-size: 1.75rem;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #FFFFFF !important;
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--brand-lime);
            box-shadow: 0 0 0 4px var(--brand-lime-translucent);
            color: #FFFFFF;
        }

        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .btn-login-submit {
            background-color: var(--brand-lime);
            color: var(--brand-forest-dark);
            font-weight: 700;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.85rem;
            width: 100%;
            transition: all 0.25s ease;
            font-size: 0.95rem;
        }

        .btn-login-submit:hover {
            background-color: var(--brand-lime-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--brand-lime-translucent);
            color: var(--brand-forest-dark);
        }
    </style>
</head>
<body>
    <div class="login-bg-shape login-bg-shape-1"></div>
    <div class="login-bg-shape login-bg-shape-2"></div>

    <div class="login-card-container">
        <div class="login-card-custom">
            <div class="text-center mb-4">
                <div class="login-brand">
                    <i class="bi bi-asterisk"></i>
                    <span>Tanikuy</span>
                </div>
                <div class="text-white-50 small">Sistem Manajemen Inventory & POS Pertanian</div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger py-2 px-3 small border-0 bg-danger text-white rounded mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 small border-0 bg-danger text-white rounded mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label small text-white-50 fw-semibold">Email Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text border-0" style="background: rgba(255, 255, 255, 0.08); color: var(--brand-lime);">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" name="email" id="email" class="form-control form-control-custom" value="{{ old('email', 'admin@tanikuy.com') }}" required autofocus placeholder="nama@tanikuy.com">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label small text-white-50 fw-semibold m-0">Kata Sandi</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text border-0" style="background: rgba(255, 255, 255, 0.08); color: var(--brand-lime);">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" name="password" id="password" class="form-control form-control-custom" value="password" required placeholder="••••••••">
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked style="background-color: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);">
                    <label class="form-check-label small text-white-50" for="remember">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <button type="submit" class="btn btn-login-submit d-flex align-items-center justify-content-center gap-2">
                    <span>Masuk ke Dashboard</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 text-center text-white-50 small">
                Demo Akun: <span class="text-white fw-bold">admin@tanikuy.com</span> &bull; Sandi: <span class="text-white fw-bold">password</span>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
