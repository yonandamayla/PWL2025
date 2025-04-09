<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BluePos | Daftar</title>

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
    <!-- Custom Register Style -->
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body class="hold-transition register-page animate__animated animate__fadeIn">
    <!-- Simple Background with Bubbles -->
    <div class="water-bg">
        <!-- Bubbles -->
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
        <div class="bubble bubble-4"></div>
        <div class="bubble bubble-5"></div>
    </div>

    <div class="register-box">
        <div class="login-logo mb-4 animate__animated animate__fadeInDown">
            <a href="{{ url('/') }}"><i class="fas fa-shopping-cart"></i> <span>BluePos</span></a>
        </div>

        <div class="card login-card animate__animated animate__fadeInUp">
            <div class="card-header text-center">
                <h4 class="text-white mb-0 font-weight-bold">Daftar Akun Baru</h4>
                <p class="text-white-50 mb-0 mt-2">Silakan isi informasi di bawah ini</p>
            </div>
            
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger animate__animated animate__headShake mb-4">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-circle mr-3 mt-1"></i>
                            <div>
                                <strong>Gagal mendaftar</strong>
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form id="registerForm" action="{{ url('/register') }}" method="post">
                    @csrf
                    
                    <div class="form-floating mb-4">
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder=" " 
                            value="{{ old('name') }}" 
                            required 
                            autofocus
                        >
                        <label for="name"><i class="fas fa-user mr-2"></i>Nama Lengkap</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder=" " 
                            value="{{ old('email') }}" 
                            required 
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
                            autocomplete="new-password"
                        >
                        <label for="password"><i class="fas fa-lock mr-2"></i>Kata Sandi</label>
                        <button type="button" class="password-toggle" id="passwordToggle" tabindex="-1">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="form-control" 
                            placeholder=" " 
                            required
                            autocomplete="new-password"
                        >
                        <label for="password_confirmation"><i class="fas fa-check-circle mr-2"></i>Konfirmasi Kata Sandi</label>
                        <button type="button" class="password-toggle" id="confirmPasswordToggle" tabindex="-1">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            Saya setuju dengan <a href="#">Syarat dan Ketentuan</a> dan <a href="#">Kebijakan Privasi</a>
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-7">
                            <button type="submit" id="registerBtn" class="btn btn-primary btn-block mb-3">
                                <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                            </button>
                        </div>
                        <div class="col-5">
                            <a href="{{ url('/login') }}" class="btn btn-outline-primary btn-block mb-3">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                            </a>
                        </div>
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
    <!-- Custom Register JavaScript -->
    <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>