<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/landingPage') }}/favicon/favicon.ico" type="image/x-icon" />

    <!-- Map CSS -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css" />

    <!-- Libs CSS -->
    <link rel="stylesheet" href="{{ asset('assets/landingPage') }}/css/libs.bundle.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/landingPage') }}/css/theme.bundle.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        #isi_tentang {
            font-family: 'Nunito', sans-serif;
        }
    </style>
    <!-- Title -->
    <title>E-VAKU</title>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/landingPage') }}/img/logoo.png" class="navbar-brand-img" alt="...">
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fe fe-x"></i>
                </button>
                <!-- Button -->
                <a href="{{ url('/') }}" class="navbar-btn btn btn-sm btn-secondary ms-auto me-2">
                    <span class="fe fe-home d-none d-md-inline p-0 m-0"></span> Beranda
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="navbar-btn btn btn-sm btn-success lift">
                        <span class="fe fe-monitor d-none d-md-inline p-0 m-0"></span> Dashboard
                    </a>
                @else
                    <a href="{{ url('/login') }}" class="navbar-btn btn btn-sm btn-success lift">
                        <span class="fe fe-log-in d-none d-md-inline p-0 m-0"></span> Masuk
                    </a>
                @endauth
            </div>

        </div>
    </nav>

    <!-- WELCOME -->
    <section class="pt-5 pt-md-6 mb-10">

        <div class="container" id="isi_tentang">
            <h2 class="text-center fw-bold mb-5">Tentang</h2>
            {!! $tentang !!}
        </div>

    </section>

    <!-- FOOTER -->
    <footer class="py-2 bg-dark fixed-bottom mt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    {{-- copyright --}}
                    <p class="text-center text-muted small mb-0">
                        &copy; @php
                            echo date('Y');
                        @endphp <a href="https://sultengprov.go.id/" target="_blank"
                            class="text-muted">Pemerintah Daerah Provinsi Sulawesi Tengah </a>
                        {{-- <h6 class="text-white">Copy</h6> --}}
                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->
    </footer>

    <!-- JAVASCRIPT -->
    <!-- Map JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>

    <!-- Vendor JS -->
    <script src="{{ asset('assets/landingPage') }}/js/vendor.bundle.js"></script>

    <!-- Theme JS -->
    <script src="{{ asset('assets/landingPage') }}/js/theme.bundle.js"></script>

</body>

</html>
