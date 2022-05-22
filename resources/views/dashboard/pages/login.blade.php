<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('assets/dashboard') }}/img/icon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/dashboard') }}/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ['{{ asset('assets/dashboard') }}/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/dashboard') }}/css/atlantis.css">
</head>

<body class="login">
    <div class="wrapper wrapper-login wrapper-login-full p-0">
        <div
            class="login-aside w-50 d-flex flex-column align-items-center justify-content-center text-center bg-success-gradient">
            <h1 class="title fw-bold text-white mb-3">E-VAKU</h1>
            <p class="subtitle text-white op-7">Elektronik Verifikasi Pertanggung Jawaban Keuangan</p>
        </div>
        <div class="login-aside w-50 d-flex align-items-center justify-content-center bg-white">
            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="container container-login container-transparent animated fadeIn">
                    <h3 class="text-center">Masuk Ke Aplikasi</h3>
                    <div class="login-form">
                        <div class="form-group">
                            <label for="email" class="placeholder"><b>Email</b></label>
                            <input id="email" name="email" type="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="placeholder"><b>Password</b></label>
                            <div class="position-relative">
                                <input id="password" name="password" type="password" class="form-control" required>
                                <div class="show-password">
                                    <i class="icon-eye"></i>
                                </div>
                            </div>
                        </div>
                        @if (session('error'))
                            <div class="ml-2 span text-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="form-group form-action-d-flex mb-3">
                            <button class="btn btn-success col-md-5 mt-3 mt-sm-0 fw-bold ml-auto"
                                type="submit">Masuk</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('assets/dashboard') }}/js/core/jquery.3.2.1.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/js/atlantis.min.js"></script>
</body>

</html>
