<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-success">
                <li class="nav-item" id="dashboard">
                    <a href="{{ url('/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item" id="scan-qrcode">
                    <a href="{{ url('/scan-qrcode') }}">
                        <i class="fas fa-qrcode"></i>
                        <p>Scan QrCode</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Dokumen Pelaksana Anggaran (DPA)</h4>
                </li>
                <li class="nav-item" id="surat-penyediaan-dana">
                    <a href="{{ url('surat-penyediaan-dana') }}">
                        <i class="fas fa-table"></i>
                        <p>Surat Penyediaan Dana</p>
                    </a>
                </li>
                <li class="nav-item" id="tabel-pelaksanaan-anggaran">
                    <a href="{{ url('tabel-dpa') }}">
                        <i class="fas fa-table"></i>
                        <p>Tabel Pelaksanaan Anggaran</p>
                    </a>
                </li>
                <li class="nav-item" id="statistik-dpa">
                    <a href="{{ url('statistik-dpa') }}">
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
                    <a data-toggle="collapse" href="#menu-spp-gu">
                        <i class="far fa-envelope"></i>
                        <p>SPP GU</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="menu-spp-gu">
                        <ul class="nav nav-collapse">
                            <li id="spp-gu-spj">
                                <a href="{{ url('spj-gu') }}">
                                    <span class="sub-item">SPJ</span>
                                </a>
                            </li>
                            <li id="spp-gu-spp">
                                <a href="{{ url('spp-gu') }}">
                                    <span class="sub-item">SPP</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (in_array(Auth::user()->role, [
                        'Admin',
                        'Bendahara Pengeluaran',
                        'Bendahara Pengeluaran Pembantu',
                        'Bendahara Pengeluaran Pembantu Belanja Hibah',
                    ]))
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
                    <li class="nav-item" id="master-program">
                        <a href="{{ url('/master-data/program') }}">
                            <i class="far fa-list-alt"></i>
                            <p>Program</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-kategori-spp-ls">
                        <a href="{{ url('/master-data/kategori-spp-ls') }}">
                            <i class="far fa-list-alt"></i>
                            <p>Kategori SPP LS</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role == 'Admin')
                    <li class="nav-item" id="master-sekretariat-daerah">
                        <a href="{{ url('/master-data/sekretariat-daerah') }}">
                            <i class="fas fa-building"></i>
                            <p>Sekretariat Daerah</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-tahun">
                        <a href="{{ url('/master-data/tahun') }}">
                            <i class="fas fa-calendar"></i>
                            <p>Tahun</p>
                        </a>
                    </li>
                    <li class="nav-item" id="master-akun">
                        <a data-toggle="collapse" href="#menu-akun">
                            <i class="fas fa-user-circle"></i>
                            <p>Akun</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="menu-akun">
                            <ul class="nav nav-collapse">
                                <li id="akun-sekretariat">
                                    <a href="{{ url('master-data/akun') }}">
                                        <span class="sub-item">Sekretariat</span>
                                    </a>
                                </li>
                                <li id="akun-lainnya">
                                    <a href="{{ url('master-data/akun-lainnya') }}">
                                        <span class="sub-item">Lainnya</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item" id="master-tentang">
                        <a href="{{ url('/master-data/tentang') }}">
                            <i class="fas fa-info-circle"></i>
                            <p>Tentang</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
