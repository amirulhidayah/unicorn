<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-success">
                <li class="nav-item">
                    <a href="calendar.html">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Dokumen Pelaksana Anggaran (DPA)</h4>
                </li>
                <li class="nav-item" id="tabel-dpa">
                    <a href="{{ url('tabel-dpa') }}">
                        <i class="fas fa-table"></i>
                        <p>Tabel</p>
                    </a>
                </li>
                <li class="nav-item" id="spd">
                    <a href="{{ url('spd') }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Statistik</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Surat Perintah Pembayaran (SPP)</h4>
                </li>
                <li class="nav-item" id="spp-up">
                    <a href="{{ url('spp-up') }}">
                        <i class="far fa-envelope"></i>
                        <p>SPP UP</p>
                    </a>
                </li>
                <li class="nav-item" id="spp-tu">
                    <a href="{{ url('spp-tu') }}">
                        <i class="far fa-envelope"></i>
                        <p>SPP TU</p>
                    </a>
                </li>
                <li class="nav-item" id="spp-ls">
                    <a href="{{ url('spp-ls') }}">
                        <i class="far fa-envelope"></i>
                        <p>SPP LS</p>
                    </a>
                </li>
                <li class="nav-item" id="spp-gu">
                    <a href="{{ url('spp-gu') }}">
                        <i class="far fa-envelope"></i>
                        <p>SPP GU</p>
                    </a>
                </li>
                @if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran']))
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Master Data</h4>
                    </li>
                @endif
                @if (Auth::user()->role == 'Admin')
                    <li class="nav-item" id="master-daftar-dokumen-spp-up">
                        <a href="{{ url('/master-data/daftar-dokumen-spp-up') }}">
                            <i class="far fa-file-pdf"></i>
                            <p>Dokumen SPP UP</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-daftar-dokumen-spp-tu">
                        <a href="{{ url('/master-data/daftar-dokumen-spp-tu') }}">
                            <i class="far fa-file-pdf"></i>
                            <p>Dokumen SPP TU</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-daftar-dokumen-spp-ls">
                        <a href="{{ url('/master-data/daftar-dokumen-spp-ls') }}">
                            <i class="far fa-file-pdf"></i>
                            <p>Dokumen SPP LS</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-daftar-dokumen-spp-gu">
                        <a href="{{ url('/master-data/daftar-dokumen-spp-gu') }}">
                            <i class="far fa-file-pdf"></i>
                            <p>Dokumen SPP GU</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-program-dpa">
                        <a href="{{ url('/master-data/program-dpa') }}">
                            <i class="far fa-list-alt"></i>
                            <p>Program (DPA)</p>
                        </a>
                    </li>
                @endif
                @if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran']))
                    <li class="nav-item" id="master-program-spp">
                        <a href="{{ url('/master-data/program-spp') }}">
                            <i class="far fa-list-alt"></i>
                            <p>Program (SPP)</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role == 'Admin')
                    <li class="nav-item" id="master-biro-organisasi">
                        <a href="{{ url('/master-data/biro-organisasi') }}">
                            <i class="fas fa-building"></i>
                            <p>Biro Organisasi</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-tahun">
                        <a href="{{ url('/master-data/tahun') }}">
                            <i class="fas fa-calendar"></i>
                            <p>Tahun</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-akun">
                        <a href="{{ url('/master-data/akun') }}">
                            <i class="fas fa-user-circle"></i>
                            <p>Akun</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
