<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blue POS | Login</title>

    <!-- Google Font: Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!-- Animation CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom Login Style -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="hold-transition login-page animate__animated animate__fadeIn">
    <!-- Simple Background with Bubbles -->
    <div class="water-bg">
        <!-- Bubbles -->
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
        <div class="bubble bubble-4"></div>
        <div class="bubble bubble-5"></div>
    </div>

    <!-- Demo Account Button -->
    <button id="demoToggle" class="demo-toggle animate__animated animate__fadeIn" title="Lihat akun demo">
        <i class="fas fa-info"></i>
    </button>

    <!-- Demo Account Info - Reorganized -->
    <div id="demoAccounts" class="demo-accounts">
        <div class="demo-accounts-header">
            <i class="fas fa-user-friends"></i>
            <h6>Akun Demo</h6>
        </div>
        
        <div class="demo-account">
            <div class="account-type-label type-admin mb-1">Admin</div>
            <div class="account-details">
                <strong>admin@example.com</strong>
                <small class="text-muted d-block">Kata Sandi: password123</small>
            </div>
        </div>

        <div class="demo-account">
            <div class="account-type-label type-kasir mb-1">Kasir</div>
            <div class="account-details">
                <strong>kasir@example.com</strong>
                <small class="text-muted d-block">Kata Sandi: password123</small>
            </div>
        </div>

        <div class="demo-account">
            <div class="account-type-label type-customer mb-1">Pelanggan</div>
            <div class="account-details">
                <strong>customer1@example.com</strong>
                <small class="text-muted d-block">Kata Sandi: password123</small>
            </div>
        </div>

        <div class="demo-accounts-footer">
            Klik pada akun untuk mengisi data login
        </div>
    </div>

    <div class="login-box">
        <div class="login-logo mb-4 animate__animated animate__fadeInDown">
            <a href="{{ url('/') }}"><i class="fas fa-shopping-cart"></i> <span>BluePos</span></a>
        </div>

        <div class="card login-card animate__animated animate__fadeInUp">
            <div class="card-header text-center">
                <h4 class="text-white mb-0 font-weight-bold">Selamat Datang!</h4>
                <p class="text-white-50 mb-0 mt-2">Silahkan masuk untuk berbelanja</p>
            </div>
            
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger animate__animated animate__headShake mb-4">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-circle mr-3 mt-1"></i>
                            <div>
                                <strong>Gagal masuk</strong>
                                <p class="mb-0">Periksa kembali email dan kata sandi Anda.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form id="loginForm" action="{{ url('/login') }}" method="post">
                    @csrf
                    
                    <div class="form-floating mb-4">
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder=" " 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            autocomplete="email"
                        >
                        <label for="email"><i class="fas fa-envelope mr-2"></i>Email</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder=" " 
                            required
                            autocomplete="current-password"
                        >
                        <label for="password"><i class="fas fa-lock mr-2"></i>Kata Sandi</label>
                        <button type="button" class="password-toggle" id="passwordToggle" tabindex="-1">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        <a href="#" class="text-decoration-none" style="color: var(--primary); font-weight: 500; font-size: 14px;">
                            Lupa Kata Sandi?
                        </a>
                    </div>

                    <button type="submit" id="loginBtn" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </button>
                    
                    <div class="text-center">
                        <span style="font-size: 14px; color: #6b7280;">Belum punya akun?</span>
                        <a href="{{ url('/register') }}" class="ml-1 text-decoration-none" style="color: var(--primary); font-weight: 500;">
                            Daftar sekarang
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <!-- Custom Login JavaScript -->
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>