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
                @auth
                    <a href="{{ url('/dashboard') }}" class="navbar-btn btn btn-sm btn-success lift ms-auto">
                        <span class="fe fe-monitor d-none d-md-inline p-0 m-0"></span> Beranda
                    </a>
                @else
                    <a href="{{ url('/login') }}" class="navbar-btn btn btn-sm btn-success lift ms-auto">
                        <span class="fe fe-log-in d-none d-md-inline p-0 m-0"></span> Masuk
                    </a>
                @endauth
            </div>

        </div>
    </nav>

    <!-- WELCOME -->
    <section class="position-relative pt-12 pt-md-14 mt-n11">

        <!-- Shape -->
        <div class="shape shape-fluid-x shape-blur-2 text-light">
            <svg viewBox="0 0 1313 768" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M442.794 768c163.088 0 305.568-88.227 382.317-219.556l.183.556s.249-.749.762-2.181a440.362 440.362 0 0033.192-71.389C901.996 397.81 989.306 277.09 1144.29 206l-.92-.693C1230.34 171.296 1295.63 94.049 1312.83 0H1.294v295.514c-.663 9.909-1 19.908-1 29.986 0 244.386 198.114 442.5 442.5 442.5z"
                    fill="currentColor" />
            </svg>
        </div>

        <!-- Content -->
        <div class="container">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-12 col-md-5 col-lg-6 order-md-1">

                    <!-- Image -->
                    <img src="{{ asset('assets/landingPage') }}/img/illustrations/verifikasi.png" alt="..."
                        class="img-fluid mw-110 float-end mb-6 mb-md-0" data-aos="fade-right">

                </div>
                <div class="col-12 col-md-7 col-lg-6 order-md-2 px-6" data-aos="fade-left">

                    <!-- Heading -->
                    <h1 class="display-3 text-center text-md-start mb-2">
                        Selamat Datang di Aplikasi <span class="text-success">E-VAKU</span>
                    </h1>
                    <span class="text-success mb-5" style="font-size: 22px">(Elektronik Verifikasi Pertanggung Jawaban
                        Keuangan)</span>

                    <div class="text-center text-md-start mt-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-success shadow lift me-1">
                                <span class="fe fe-monitor d-none d-md-inline p-0 m-0"></span> Beranda
                            </a>
                        @else
                            <a href="{{ url('login') }}" class="btn btn-success shadow lift me-1">
                                <span class="fe fe-log-in d-none d-md-inline p-0 m-0"></span> Masuk
                            </a>
                        @endauth
                    </div>

                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->

    </section>

    <!-- HUB -->
    <section class="py-8 pt-md-11 pb-md-9">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 text-center" data-aos="fade-up" data-aos-delay="50">

                    <!-- Icon -->
                    <div class="icon text-success mb-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <g fill="none" fill-rule="evenodd">
                                <path d="M0 0h24v24H0z"></path>
                                <path d="M18 2h2a3 3 0 013 3v14a3 3 0 01-3 3h-2V2z" fill="#335EEA" opacity=".3">
                                </path>
                                <path
                                    d="M5 2h12a3 3 0 013 3v14a3 3 0 01-3 3H5a1 1 0 01-1-1V3a1 1 0 011-1zm7 9a2 2 0 100-4 2 2 0 000 4zm-5 5.5c-.011.162.265.5.404.5h9.177c.418 0 .424-.378.418-.5-.163-3.067-2.348-4.5-5.008-4.5-2.623 0-4.775 1.517-4.99 4.5z"
                                    fill="#335EEA"></path>
                            </g>
                        </svg>
                    </div>

                    <!-- Heading -->
                    <h3 class="mb-3">
                        Kontak
                    </h3>

                    <!-- Text -->
                    <p class="text-muted mb-0 mb-md-0">
                    <ul class="list-unstyled text-muted mb-6 mb-md-8 mb-lg-0">
                        <li class="mb-1">
                            <span class="fe fe-mail"></span> ppidprovsulteng@gmail.com
                        </li>
                    </ul>
                    </p>

                </div>
                <div class="col-12 col-md-6 text-center" data-aos="fade-up" data-aos-delay="100">

                    <!-- Icon -->
                    <div class="icon text-success mb-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <g fill="none" fill-rule="evenodd">
                                <path d="M0 0h24v24H0z"></path>
                                <path
                                    d="M12 21a9 9 0 110-18 9 9 0 010 18zm2.165-13.645l-4.554 3.007a.5.5 0 00-.224.388l-.327 5.448a.5.5 0 00.775.447l4.554-3.007a.5.5 0 00.224-.388l.327-5.448a.5.5 0 00-.775-.447z"
                                    fill="#335EEA"></path>
                            </g>
                        </svg>
                    </div>

                    <!-- Heading -->
                    <h3>
                        Alamat
                    </h3>

                    <!-- Text -->
                    <p class="text-muted mb-0">
                        <i class="fe fe-map-pin"></i> Jl. Sam Ratulangi 101 Palu, Sulawesi Tengah
                    </p>

                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->
    </section>

    <!-- FOOTER -->
    <footer class="py-2 bg-dark">
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
