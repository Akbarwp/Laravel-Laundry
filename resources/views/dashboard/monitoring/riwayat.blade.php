@extends('dashboard.layouts.main')

@section('js')
    <script>
        $(document).ready(function() {
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

            if (document.querySelector("#chart-riwayat-monitoring-gamis")) {
                let bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                let hasilBulan = [];
                @foreach ($riwayatMonitoringGamis as $item)
                    hasilBulan.push({{ $item['hasil'] }});
                @endforeach

                let ctx1 = document.getElementById("chart-riwayat-monitoring-gamis").getContext("2d");
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
        });
    </script>
@endsection

@section('container')
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Tabel Monitoring Gamis --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div>
                        <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                        <h6 class="font-bold dark:text-white">UMR {{ $umr->regional . ' ' . $umr->tahun }}: <span class="text-blue-500">Rp{{ number_format($umr->upah, 2, ',', '.') }}</span></h6>
                    </div>
                    <div>
                        <a href="{{ route("monitoring") }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                            <i class="ri-arrow-left-line"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Gamis
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Upah
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Status
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Bulan
                                    </th>
                                    <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Tahun
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monitoring as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->nama_gamis }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->upah, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                @if ($item->status == 'Gamis')
                                                    <span class="badge badge-primary">{{ $item->status }}</span>
                                                @elseif ($item->status == 'Lulus')
                                                    <span class="badge badge-accent">{{ $item->status }}</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->bulan }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->tahun }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Monitoring Gamis --}}

            {{-- Awal Tabel Diagram --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <form action="{{ route("monitoring.gamis.riwayat", ['gamis' => $gamis]) }}" method="get" enctype="multipart/form-data" class="my-3">
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100 text-lg">Tahun</span>
                                </div>
                                <input type="number" min="0" name="tahun" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ \Carbon\Carbon::now()->format('Y') }}" />
                            </label>
                            <button type="submit" class="btn btn-accent btn-sm uppercase mt-3">Filter</button>
                            <a href="{{ route("monitoring.gamis.riwayat", ['gamis' => $gamis]) }}" class="btn btn-neutral btn-sm uppercase mt-3">Reset</a>
                        </form>
                        <div>
                            <canvas id="chart-riwayat-monitoring-gamis" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Diagram --}}
        </div>
    </div>
@endsection
