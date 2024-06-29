@extends("dashboard.layouts.main")

@section('js')
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                    },
                },
                order: [],
                pagingType: 'full_numbers',
            });
        });

        @role(['lurah', 'manajer_laundry', 'pegawai_laundry', 'gamis'])
            // Pendapatan Per Bulan
            if (document.querySelector("#chart-pendapatan-bulanan")) {
                let bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                let hasilBulan = [];
                @foreach ($pendapatanBulanan as $item)
                    hasilBulan.push({{ $item['hasil'] }});
                @endforeach

                let ctx1 = document.getElementById("chart-pendapatan-bulanan").getContext("2d");
                let gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
                gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
                new Chart(ctx1, {
                    type: "line",
                    data: {
                        labels: bulan,
                        datasets: [{
                            label: "Rp",
                            tension: 0.4,
                            borderWidth: 0,
                            pointRadius: 0,
                            borderColor: "#5e72e4",
                            backgroundColor: gradientStroke1,
                            borderWidth: 3,
                            fill: true,
                            data: hasilBulan,
                            maxBarThickness: 6
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        scales: {
                            y: {
                                grid: {
                                    drawBorder: false,
                                    display: true,
                                    drawOnChartArea: true,
                                    drawTicks: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    display: true,
                                    padding: 10,
                                    color: '#fbfbfb',
                                    font: {
                                        size: 11,
                                        family: "Open Sans",
                                        style: 'normal',
                                        lineHeight: 2
                                    },
                                }
                            },
                            x: {
                                grid: {
                                    drawBorder: false,
                                    display: false,
                                    drawOnChartArea: false,
                                    drawTicks: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    display: true,
                                    color: '#ccc',
                                    padding: 20,
                                    font: {
                                        size: 11,
                                        family: "Open Sans",
                                        style: 'normal',
                                        lineHeight: 2
                                    },
                                }
                            },
                        },
                    },
                });
            }

            // Pendapatan Per Tahun
            if (document.querySelector("#chart-pendapatan-tahunan")) {
                let tahun = [];
                let hasilTahun = [];
                @foreach ($pendapatanTahunan as $item)
                    tahun.push({{ $item['tahun'] }});
                    hasilTahun.push({{ $item['hasil'] }});
                @endforeach

                let ctx1 = document.getElementById("chart-pendapatan-tahunan").getContext("2d");
                let gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
                gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
                new Chart(ctx1, {
                    type: "line",
                    data: {
                        labels: tahun,
                        datasets: [{
                            label: "Rp",
                            tension: 0.4,
                            borderWidth: 0,
                            pointRadius: 0,
                            borderColor: "#5e72e4",
                            backgroundColor: gradientStroke1,
                            borderWidth: 3,
                            fill: true,
                            data: hasilTahun,
                            maxBarThickness: 6
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        scales: {
                            y: {
                                grid: {
                                    drawBorder: false,
                                    display: true,
                                    drawOnChartArea: true,
                                    drawTicks: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    display: true,
                                    padding: 10,
                                    color: '#fbfbfb',
                                    font: {
                                        size: 11,
                                        family: "Open Sans",
                                        style: 'normal',
                                        lineHeight: 2
                                    },
                                }
                            },
                            x: {
                                grid: {
                                    drawBorder: false,
                                    display: false,
                                    drawOnChartArea: false,
                                    drawTicks: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    display: true,
                                    color: '#ccc',
                                    padding: 20,
                                    font: {
                                        size: 11,
                                        family: "Open Sans",
                                        style: 'normal',
                                        lineHeight: 2
                                    },
                                }
                            },
                        },
                    },
                });
            }
        @endrole
    </script>
@endsection

@section("container")
    <div>
        <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
            <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6">
                <h6 class="font-bold dark:text-white">Tanggal: <span class="text-blue-500">{{ \Carbon\Carbon::now()->format('d F Y') }}</span></h6>
            </div>
        </div>

        @role(['lurah', 'manajer_laundry', 'pegawai_laundry'])
            <!-- row 1 -->
            <div class="-mx-3 mb-3 flex flex-wrap">
                <!-- Jumlah Cabang -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                            {{ $userRole == 'lurah' ? 'Jumlah Cabang Aktif' : 'Cabang' }}
                                        </p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $userRole == 'lurah' ? $jmlCabang : ($cabang ? $cabang->nama : 'Cabang Non Aktif') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-home-smile-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah User -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Jumlah User Aktif</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">{{ $jmlUser }}</h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-earth-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UMR -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">UMR <span class="capitalize">{{ $umr->regional }}</span> ({{ $umr->tahun }})</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">Rp{{ number_format($umr->upah, 2, ',', '.') }}</h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-file-paper-2-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Gamis -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                            Jumlah Gamis
                                        </p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $jmlGamis }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-parent-fill relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- row 2 -->
            <div class="-mx-3 mb-3 flex flex-wrap">
                <!-- Transaksi: Status Baru -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-info">Baru</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiBaru }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi: Status Proses -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-warning">Proses</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiProses }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi: Status Siap Diambil -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-primary">Siap Ambil</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiSiapDiambil }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- row 3 -->
            <div class="-mx-3 mb-3 flex flex-wrap">
                <!-- Transaksi: Status Penjemputan -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-secondary">Jemput</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiPenjemputan }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi: Status Pengantaran -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-secondary">Antar</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiPengantaran }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi: Status Selesai -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-success">Selesai</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiSelesai }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Awal Tabel Jadwal Layanan --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div>
                        <h6 class="font-bold dark:text-white">Transaksi Hari Ini</h6>
                        <h6 class="font-bold dark:text-white">Pendapatan: <span class="text-blue-500">Rp{{ number_format($pendapatanHari,2,',','.') }}</span></h6>
                    </div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Waktu
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Layanan Prioritas
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Total Bayar
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Pelanggan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Pegawai
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Gamis
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Status
                                    </th>
                                    @role('lurah')
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Cabang
                                        </th>
                                    @endrole
                                    <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwalLayanan as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ \Carbon\Carbon::parse($item->waktu)->format('d F Y H:i:s') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->layananPrioritas->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->total_bayar_akhir, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->pelanggan->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                @php
                                                    $userRole = $item->pegawai->roles[0]->name;
                                                @endphp
                                                @if ($userRole == 'manajer_laundry')
                                                    {{ $item->pegawai->manajer->first()->nama }}
                                                @elseif ($userRole == 'pegawai_laundry')
                                                    {{ $item->pegawai->pegawai->first()->nama }}
                                                @elseif ($userRole == 'lurah')
                                                    {{ $item->pegawai->lurah->first()->nama }}
                                                @endif
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->gamis_id ? $item->gamis->nama : "-" }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                <x-kolom-status-transaksi :value="$item->status" />
                                            </p>
                                        </td>
                                        @role('lurah')
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->cabang_nama }}
                                                </p>
                                            </td>
                                        @endrole
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                @role('lurah')
                                                    <a href="{{ route("transaksi.lurah.view", ['cabang' => $item->cabang_slug, 'transaksi' => $item->id, 'isJadwal' => true]) }}" class="btn btn-outline btn-info btn-sm">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </a>
                                                @endrole
                                                @role(['manajer_laundry', 'pegawai_laundry'])
                                                    <a href="{{ route("transaksi.view", ['transaksi' => $item->id, 'isJadwal' => true]) }}" class="btn btn-outline btn-info btn-sm">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </a>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Jadwal Layanan --}}

            <!-- row 4 -->
            <div class="-mx-3 mt-6 flex flex-wrap">
                <div class="mt-0 w-full max-w-full px-3 lg:w-1/2 lg:flex-none">
                    <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border shadow-xl">
                        <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pb-0 pt-4">
                            <h6 class="capitalize dark:text-white">Pendapatan Bulanan: <span class="font-bold text-blue-700 dark:text-white">{{ \Carbon\Carbon::now()->format('Y') }}</span></h6>
                        </div>
                        <div class="flex-auto p-4">
                            <div>
                                <canvas id="chart-pendapatan-bulanan" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-0 w-full max-w-full px-3 lg:w-1/2 lg:flex-none">
                    <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border shadow-xl">
                        <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pb-0 pt-4">
                            <h6 class="capitalize dark:text-white">Pendapatan Tahunan</h6>
                        </div>
                        <div class="flex-auto p-4">
                            <div>
                                <canvas id="chart-pendapatan-tahunan" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endrole

        @role(['gamis'])
            <!-- row 1 -->
            <div class="-mx-3 mb-3 flex flex-wrap">
                <!-- Cabang -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                            Cabang
                                        </p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $cabang ? $cabang->nama : 'Cabang Non Aktif' }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-home-smile-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UMR -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">UMR <span class="capitalize">{{ $umr->regional }}</span> ({{ $umr->tahun }})</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">Rp{{ number_format($umr->upah, 2, ',', '.') }}</h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-file-paper-2-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Gamis -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                            Jumlah Gamis
                                        </p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $jmlGamis }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-parent-fill relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- row 2 -->
            <div class="-mx-3 mb-3 flex flex-wrap">
                <!-- Transaksi: Status Baru -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-info">Baru</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiBaru }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi: Status Proses -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-warning">Proses</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiProses }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi: Status Siap Diambil -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-primary">Siap Ambil</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiSiapDiambil }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- row 3 -->
            <div class="-mx-3 mb-3 flex flex-wrap">
                <!-- Transaksi: Status Penjemputan -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-secondary">Jemput</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiPenjemputan }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi: Status Pengantaran -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-secondary">Antar</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiPengantaran }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi: Status Selesai -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">Transaksi:</p>
                                        <p class="font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60 text-success">Selesai</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $transaksiSelesai }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-lime-500 to-teal-500 text-center">
                                        <i class="ri-shopping-bag-4-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- row 4 -->
            <div class="-mx-3 mt-6 flex flex-wrap">
                <div class="mt-0 w-full max-w-full px-3 lg:w-1/2 lg:flex-none">
                    <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border shadow-xl">
                        <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pb-0 pt-4">
                            <h6 class="capitalize dark:text-white">Pendapatan Bulanan: <span class="font-bold text-blue-700 dark:text-white">{{ \Carbon\Carbon::now()->format('Y') }}</span></h6>
                        </div>
                        <div class="flex-auto p-4">
                            <div>
                                <canvas id="chart-pendapatan-bulanan" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-0 w-full max-w-full px-3 lg:w-1/2 lg:flex-none">
                    <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border shadow-xl">
                        <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pb-0 pt-4">
                            <h6 class="capitalize dark:text-white">Pendapatan Tahunan</h6>
                        </div>
                        <div class="flex-auto p-4">
                            <div>
                                <canvas id="chart-pendapatan-tahunan" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endrole

        @role('rw')
            <!-- row 1 -->
            <div class="-mx-3 mb-3 flex flex-wrap">
                <!-- RW -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                            RW
                                        </p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $rw->nomor_rw }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-home-smile-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UMR -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">UMR <span class="capitalize">{{ $umr->regional }}</span> ({{ $umr->tahun }})</p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">Rp{{ number_format($umr->upah, 2, ',', '.') }}</h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-file-paper-2-line relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Gamis -->
                <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                    <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                        <div class="flex-auto p-4">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-2/3 max-w-full flex-none px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                            Jumlah Gamis
                                        </p>
                                        <h5 class="mb-2 font-bold text-blue-700 dark:text-white">
                                            {{ $jmlGamis }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="basis-1/3 px-3 text-right">
                                    <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                        <i class="ri-parent-fill relative top-3 text-2xl leading-none text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endrole
    </div>
@endsection
