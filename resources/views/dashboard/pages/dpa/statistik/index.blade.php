@extends('dashboard.layouts.main')

@section('title')
    Statistik Dokumen Pelaksana Anggaran
@endsection

@push('style')
    <style>
        .table-bordered {
            border: 1px solid black !important;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid black !important;
            font-size: 13px !important;
        }
    </style>
@endpush

@section('breadcrumb')
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="#">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Statistik Dokumen Pelaksana Anggaran</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Tabel</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Statistik Dokumen Pelaksana Anggaran</div>
                        <div class="card-tools">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        @csrf
                        @if (
                            !in_array(Auth::user()->role, [
                                'Bendahara Pengeluaran',
                                'Bendahara Pengeluaran Pembantu',
                                'Bendahara Pengeluaran Pembantu Belanja Hibah',
                            ]))
                            <div class="col-6">
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Sekretariat Daerah',
                                    'id' => 'sekretariat_daerah',
                                    'name' => 'sekretariat_daerah',
                                    'class' => 'select2',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                    @slot('options')
                                        @foreach ($SekretariatDaerah as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}
                                            </option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                        @endif
                        <div class="col-6">
                            @component('dashboard.components.formElements.select', [
                                'label' => 'Tahun',
                                'id' => 'tahun',
                                'name' => 'tahun',
                                'class' => 'select2',
                                'wajib' => '<sup class="text-danger">*</sup>',
                            ])
                                @slot('options')
                                    @foreach ($tahun as $item)
                                        <option value="{{ $item->id }}">{{ $item->tahun }}</option>
                                    @endforeach
                                @endslot
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('dashboard.components.formElements.select', [
                                'label' => 'Program',
                                'id' => 'program',
                                'name' => 'program',
                                'class' => 'select2',
                                'attribute' => 'disabled',
                                'wajib' => '<sup class="text-danger">*</sup>',
                            ])
                            @endcomponent
                        </div>

                        <div class="col-6">
                            @component('dashboard.components.formElements.select', [
                                'label' => 'Kegiatan',
                                'id' => 'kegiatan',
                                'name' => 'kegiatan',
                                'class' => 'select2',
                                'attribute' => 'disabled',
                                'wajib' => '<sup class="text-danger">*</sup>',
                            ])
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('dashboard.components.formElements.select', [
                                'label' => 'Model Statistik',
                                'id' => 'model',
                                'name' => 'model',
                                'class' => 'select2',
                                'wajib' => '<sup class="text-danger">*</sup>',
                            ])
                                @slot('options')
                                    <option value="Bar Chart">Bar Chart</option>
                                    <option value="Line Chart">Line Chart</option>
                                    <option value="Pie Chart">Pie Chart</option>
                                @endslot
                            @endcomponent
                        </div>
                    </div>
                    <canvas id="statistik" height="50px" width="100px">

                    </canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"
        integrity="sha512-sW/w8s4RWTdFFSduOTGtk4isV1+190E/GghVffMA9XczdJ2MDzSzLEubKAs5h0wzgSJOQTRYyaz73L3d6RtJSg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2  "></script>

    <script>
        var role = "{{ Auth::user()->role }}";
        var roleAdmin = ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'];
        const statistik = $('#statistik');

        function setConfigStatistikBar(bulan, dataStatistik, judul, jumlahAnggaran) {
            const configStatistik = new Chart(statistik, {
                type: 'bar',
                data: {
                    labels: bulan,
                    datasets: [{
                        label: judul,
                        data: dataStatistik,
                        fill: false,
                        backgroundColor: [
                            '#C6DCE4',
                            '#C3E5AE',
                            '#68BDE1',
                            '#28FFBF',
                            '#FFE3B0',
                            '#FFCBCB',
                            '#A3E4DB',
                            '#E4CDA7',
                            '#A7C5EB',
                            '#ADC2A9',
                            '#6EBF8B',
                            '#C7D36F'
                        ],
                        tension: 0
                    }]
                },
                options: {
                    layout: {
                        padding: 20
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            labels: {
                                title: {
                                    formatter: function(value, context) {
                                        return "Rp." + formatNumber(value);
                                    },
                                    anchor: 'end',
                                    align: 'end',
                                    textAlign: 'center',
                                    backgroundColor: 'white',
                                },
                                value: {
                                    formatter: function(value, context) {
                                        return (value * 100 / jumlahAnggaran)
                                            .toFixed(2) + "%";
                                    },
                                    font: {
                                        weight: 'bold'
                                    },
                                    textAlign: 'center',
                                    // backgroundColor: 'white',
                                }
                            }
                        },
                        subtitle: {
                            display: true,
                            text: 'Anggaran Digunakan Perbulan'
                        },
                        title: {
                            display: true,
                            text: "Jumlah Anggaran : Rp." + formatNumber(jumlahAnggaran),
                            position: 'bottom',
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value, index, ticks) {
                                    return "Rp." + formatNumber(value);
                                }
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels],
            });
        }

        function setConfigStatistikLine(bulan, dataStatistik, judul, jumlahAnggaran) {
            const configStatistik = new Chart(statistik, {
                type: 'line',
                data: {
                    labels: bulan,
                    datasets: [{
                        label: judul,
                        data: dataStatistik,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0
                    }]
                },
                options: {
                    layout: {
                        padding: {
                            top: 20,
                            right: 50,
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            formatter: function(value, context) {
                                return [(value * 100 / jumlahAnggaran)
                                    .toFixed(2) + "%", "( Rp." + formatNumber(value) + " )"
                                ];
                            },
                            anchor: 'end',
                            align: 'end',
                            textAlign: 'center',
                            font: {
                                weight: 'bold'
                            },
                            backgroundColor: 'white',
                        },
                        subtitle: {
                            display: true,
                            text: 'Anggaran Digunakan Perbulan'
                        },
                        title: {
                            display: true,
                            text: "Jumlah Anggaran : Rp." + formatNumber(jumlahAnggaran),
                            position: 'bottom',
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value, index, ticks) {
                                    return "Rp." + formatNumber(value);
                                }
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels],
            });
        }

        function setConfigStatistikPie(bulan, dataStatistik, judul, jumlahAnggaran) {
            const configStatistik = new Chart(statistik, {
                type: 'pie',
                data: {
                    labels: bulan,
                    datasets: [{
                        label: judul,
                        data: dataStatistik,
                        backgroundColor: [
                            '#C6DCE4',
                            '#C3E5AE',
                            '#68BDE1',
                            '#28FFBF',
                            '#FFE3B0',
                            '#FFCBCB',
                            '#A3E4DB',
                            '#E4CDA7',
                            '#F9F3DF',
                            '#ADC2A9',
                            '#6EBF8B',
                            '#C7D36F'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    layout: {
                        padding: 50
                    },
                    plugins: {
                        datalabels: {
                            labels: {
                                title: {
                                    formatter: function(value, context) {
                                        return "( Rp." + formatNumber(value) + " )";
                                    },
                                    rotation: function(ctx) {
                                        const valuesBefore = ctx.dataset.data.slice(0, ctx.dataIndex).reduce((a,
                                                b) =>
                                            a + b, 0);
                                        const sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const rotation = ((valuesBefore + ctx.dataset.data[ctx.dataIndex] / 2) /
                                            sum *
                                            360);
                                        return rotation < 180 ? rotation - 90 : rotation + 90;
                                    },
                                    anchor: "center",
                                    backgroundColor: 'white',
                                },
                                value: {
                                    formatter: function(value, context) {
                                        return (value * 100 / jumlahAnggaran)
                                            .toFixed(2) + "%";
                                    },
                                    font: {
                                        weight: 'bold'
                                    },
                                    anchor: 'end',
                                    align: 'end',
                                    offset: 20,
                                    textAlign: 'center',
                                    // backgroundColor: 'white',
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: "Jumlah Anggaran : Rp." + formatNumber(jumlahAnggaran),
                            position: 'top',
                            layout: {
                                padding: {
                                    bottom: 100
                                }
                            },
                        }
                    }
                },
                plugins: [ChartDataLabels],
            });
        }

        function getDataStatistik() {
            var tahun = $('#tahun').val();
            var SekretariatDaerah = roleAdmin.includes(role) ? $('#sekretariat_daerah').val() :
                "{{ Auth::user()->profil->sekretariat_daerah_id }}";
            var kegiatan = $('#kegiatan').val();
            var model = $('#model').val();

            resetChart();

            if ((tahun != '') && (SekretariatDaerah != '') && (kegiatan != '') && (model != '')) {
                $.ajax({
                    url: "{{ url('statistik-dpa/get-data-statistik') }}",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        tahun_id: tahun,
                        sekretariat_daerah_id: SekretariatDaerah,
                        kegiatan_dpa_id: kegiatan
                    },
                    success: function(response) {
                        if (model == "Bar Chart") {
                            setConfigStatistikBar(response.bulan, response.data, response.judul, response
                                .jumlah_anggaran);
                        } else if (model == "Line Chart") {
                            setConfigStatistikLine(response.bulan, response.data, response.judul, response
                                .jumlah_anggaran);
                        } else {
                            setConfigStatistikPie(response.bulan, response.data, response.judul, response
                                .jumlah_anggaran);
                        }
                    }
                })
            };
        }

        function resetChart() {
            if (Chart.getChart("statistik")) {
                Chart.getChart("statistik").destroy();
            }
        }

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
        }
    </script>

    <script>
        $('#tahun').on('change', function() {
            var tahun = $(this).val();
            var SekretariatDaerah = $('#sekretariat_daerah').val();
            $('#kegiatan').html('').attr('disabled', true);
            getDataStatistik();
            $.ajax({
                url: "{{ url('list/program') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun,
                    sekretariat_daerah: SekretariatDaerah
                },
                success: function(response) {
                    $('#program').removeAttr('disabled');
                    if (response.length > 0) {
                        $('#program').html('');
                        $('#program').append('<option value="">Pilih Program</option>');
                        $.each(response, function(key, value) {
                            $('#program').append('<option value="' + value.id + '">' + value
                                .nama + " (" + value.no_rek + ")" + '</option>');
                        })
                    } else {
                        $('#program').html('');
                    }
                }
            })
        })

        $('#program').on('change', function() {
            var program = $('#program').val();
            var tahun = $('#tahun').val();
            var SekretariatDaerah = $('#sekretariat_daerah').val();
            getDataStatistik();
            $.ajax({
                url: "{{ url('list/kegiatan') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun,
                    program: program,
                    sekretariat_daerah: SekretariatDaerah
                },
                success: function(response) {
                    $('#kegiatan').removeAttr('disabled');
                    if (response.length > 0) {
                        $('#kegiatan').html('');
                        $('#kegiatan').append('<option value="">Pilih kegiatan</option>');
                        $.each(response, function(key, value) {
                            $('#kegiatan').append('<option value="' + value.id + '">' + value
                                .nama + " (" + value.no_rek + ")" + '</option>');
                        })
                    } else {
                        $('#kegiatan').html('');
                    }
                }
            })
        })

        $("#kegiatan").on('change', function() {
            getDataStatistik();
        })

        $('#sekretariat_daerah').on('change', function() {
            getDataStatistik();
        })

        $('#model').on('change', function() {
            getDataStatistik();
        })
    </script>

    <script>
        $(document).ready(function() {
            $('#statistik-dpa').addClass('active');
        })
    </script>
@endpush
