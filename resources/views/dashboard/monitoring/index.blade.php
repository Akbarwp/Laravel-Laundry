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
            $('#myTable1').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                    },
                },
                order: [],
                pagingType: 'full_numbers',
            });

            // chart-pendapatan-gamis
            if (document.querySelector("#chart-pendapatan-gamis")) {
                let bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                let hasilBulan = [];
                @foreach ($hasilPendapatanGamis as $item)
                    hasilBulan.push({{ $item['hasil'] }});
                @endforeach

                let ctx1 = document.getElementById("chart-pendapatan-gamis").getContext("2d");
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

            // chart-gamis-terdaftar
            if (document.querySelector("#chart-gamis-terdaftar")) {
                let bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                let hasilBulan = [];
                @foreach ($hasilJumlahGamis as $item)
                    hasilBulan.push({{ $item['hasil'] }});
                @endforeach

                let ctx1 = document.getElementById("chart-gamis-terdaftar").getContext("2d");
                let gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
                gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
                new Chart(ctx1, {
                    type: "line",
                    data: {
                        labels: bulan,
                        datasets: [{
                            label: "Total",
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

            // chart-status-gamis
            if (document.querySelector("#chart-status-lulus")) {
                let bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                let hasilBulan = [];
                @foreach ($hasilStatusGamis['Lulus'] as $item)
                    hasilBulan.push({{ $item['hasil'] }});
                @endforeach

                let ctx1 = document.getElementById("chart-status-lulus").getContext("2d");
                let gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
                gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
                new Chart(ctx1, {
                    type: "line",
                    data: {
                        labels: bulan,
                        datasets: [{
                            label: "Total",
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

            if (document.querySelector("#chart-status-gamis")) {
                let bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                let hasilBulan = [];
                @foreach ($hasilStatusGamis['Gamis'] as $item)
                    hasilBulan.push({{ $item['hasil'] }});
                @endforeach

                let ctx1 = document.getElementById("chart-status-gamis").getContext("2d");
                let gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
                gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
                new Chart(ctx1, {
                    type: "line",
                    data: {
                        labels: bulan,
                        datasets: [{
                            label: "Total",
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

        @if (session()->has('success'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has('error'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: '{{ $title }} Gagal Dibuat',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            })
        @endif

        function updateMonitoringGamis() {
            Swal.fire({
                title: "Perbarui Data Bulan Ini?",
                text: "Pembaruan data akan membutuhkan waktu",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#6419E6",
                cancelButtonColor: "#ff5860",
                confirmButtonText: "Perbarui Data",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Proses",
                        text: "Mohon menunggu sebentar",
                        icon: "info",
                        confirmButtonColor: "#6419E6",
                        confirmButtonText: "OK",
                    });

                    $.ajax({
                        type: "post",
                        url: "{{ route('monitoring.update.data') }}",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            // console.log(data);
                            Swal.fire({
                                title: 'Data berhasil diperbarui!',
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                        }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data gagal diperbarui!',
                            })
                        }
                    });
                }
            });
        }

        function resetMonitoringGamis() {
            Swal.fire({
                title: "Perbarui Data Dari Awal Transaksi?",
                text: "Pembaruan data akan membutuhkan waktu",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#6419E6",
                cancelButtonColor: "#ff5860",
                confirmButtonText: "Perbarui Data",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Proses",
                        text: "Mohon menunggu sebentar",
                        icon: "info",
                        confirmButtonColor: "#6419E6",
                        confirmButtonText: "OK",
                    });

                    $.ajax({
                        type: "post",
                        url: "{{ route('monitoring.reset.data') }}",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            // console.log(data);
                            Swal.fire({
                                title: 'Data berhasil diperbarui!',
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                        }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data gagal diperbarui!',
                            })
                        }
                    });
                }
            });
        }

        function edit_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('monitoring.edit.pemasukkan') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='nama_pemasukkan']").val(items[1]);
                    $("input[name='pemasukkan']").val(items[2]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }
    </script>
@endsection

@section('container')
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Modal Edit --}}
            <input type="checkbox" id="edit_button" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    <div class="mb-3 flex justify-between">
                        <h3 class="text-lg font-bold">Ubah Pemasukkan Gamis</h3>
                        <label for="edit_button" class="cursor-pointer">
                            <i class="ri-close-large-fill"></i>
                        </label>
                    </div>
                    <div>
                        <form action="{{ route('monitoring.update.pemasukkan') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" hidden>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                        <x-label-input-required :value="'Nama Pemasukkan'" />
                                    </span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <input type="text" name="nama_pemasukkan" placeholder="Nama Pemasukkan" class="input input-bordered w-full text-blue-700" required />
                                @error('nama_pemasukkan')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                        <x-label-input-required :value="'Pemasukkan'" />
                                    </span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <input type="number" min="0" step="0.01" name="pemasukkan" placeholder="Pemasukkan" class="input input-bordered w-full text-blue-700" required />
                                @error('pemasukkan')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal Edit --}}

            {{-- Awal Tabel Gamis --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div>
                        <h6 class="font-bold dark:text-white">Gamis</h6>
                    </div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable1" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Gamis
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Nama Pemasukkan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Pemasukkan
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
                                @foreach ($gamis as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->nama_pemasukkan }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->pemasukkan, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        @role('lurah')
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    @if ($item->cabang_deleted_at)
                                                        {{ $item->nama_cabang }} <span class="text-error">(non aktif)</span>
                                                    @else
                                                        {{ $item->nama_cabang }}
                                                    @endif
                                                </p>
                                            </td>
                                        @endrole
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                <label for="edit_button" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button('{{ $item->id }}')">
                                                    <i class="ri-pencil-fill text-base"></i>
                                                </label>
                                                <a href="{{ route("monitoring.gamis.riwayat", ['gamis' => $item->id]) }}" for="edit_button" class="btn btn-outline btn-primary btn-sm tooltip" data-tip="Riwayat Pendapatan">
                                                    <i class="ri-history-fill text-base"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Gamis --}}

            {{-- Awal Filter Cabang Tahun --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <form action="{{ route('monitoring') }}" method="get" enctype="multipart/form-data">
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                @role('lurah')
                                    <label class="form-control w-full lg:w-1/2">
                                        <div class="label">
                                            <span class="label-text font-semibold dark:text-slate-100 text-lg">Cabang</span>
                                        </div>
                                        <select name="cabang_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100">
                                            <option disabled selected>Semua Cabang</option>
                                            @foreach ($cabang as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                @endrole
                                <label class="form-control w-full @if(auth()->user()->hasRole('lurah'))lg:w-1/2 @else lg:w-full @endif">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Tanggal Awal</span>
                                    </div>
                                    <input type="number" min="0" value="{{ \Carbon\Carbon::now()->format('Y') }}" name="tahun" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" />
                                </label>
                            </div>
                            <button type="submit" class="btn btn-accent btn-sm uppercase mt-3">Filter</button>
                            <button type="reset" class="btn btn-neutral btn-sm uppercase mt-3">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Akhir Filter Cabang Tahun --}}

            {{-- Awal Diagram Monitoring Gamis --}}
            <div class="w-full lg:flex flex-none gap-3">
                <div class="w-full lg:w-1/2 dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <div>
                            <h6 class="font-bold dark:text-white">Pendapatan Gamis <span class="text-blue-500">({{ $tahun ? $tahun : 'Semua Tahun' }})</span></h6>
                        </div>
                    </div>
                    <div>
                        <canvas id="chart-pendapatan-gamis" height="300"></canvas>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <div>
                            <h6 class="font-bold dark:text-white">Gamis Terdaftar <span class="text-blue-500">({{ $tahun ? $tahun : 'Semua Tahun' }})</span></h6>
                        </div>
                    </div>
                    <div>
                        <canvas id="chart-gamis-terdaftar" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="w-full lg:flex flex-none gap-3">
                <div class="w-full lg:w-1/2 dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <div>
                            <h6 class="font-bold dark:text-white">Gamis Berstatus Lulus <span class="text-blue-500">({{ $tahun ? $tahun : 'Semua Tahun' }})</span></h6>
                        </div>
                    </div>
                    <div>
                        <canvas id="chart-status-lulus" height="300"></canvas>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <div>
                            <h6 class="font-bold dark:text-white">Gamis Berstatus Gamis <span class="text-blue-500">({{ $tahun ? $tahun : 'Semua Tahun' }})</span></h6>
                        </div>
                    </div>
                    <div>
                        <canvas id="chart-status-gamis" height="300"></canvas>
                    </div>
                </div>
            </div>
            {{-- Akhir Diagram Monitoring Gamis --}}

            {{-- Awal Tabel Monitoring Gamis --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div>
                        <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                        <h6 class="font-bold dark:text-white">UMR {{ $umr->regional . ' ' . $umr->tahun }}: <span class="text-blue-500">Rp{{ number_format($umr->upah, 2, ',', '.') }}</span></h6>
                    </div>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
                        <button class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-2 lg:mb-0 inline-block cursor-pointer rounded-lg border border-solid border-yellow-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-yellow-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2" onclick="return updateMonitoringGamis()">
                            <i class="ri-arrow-up-line"></i>
                            Perbarui Data Bulan Ini
                        </button>
                        <button class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2" onclick="return resetMonitoringGamis()">
                            <i class="ri-loop-right-line"></i>
                            Perbarui Data Keseluruhan
                        </button>
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
                                    @role('lurah')
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Cabang
                                        </th>
                                    @endrole
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
                                        @role('lurah')
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    @if ($item->cabang_deleted_at)
                                                        {{ $item->nama_cabang }} <span class="text-error">(non aktif)</span>
                                                    @else
                                                        {{ $item->nama_cabang }}
                                                    @endif
                                                </p>
                                            </td>
                                        @endrole
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Monitoring Gamis --}}
        </div>
    </div>
@endsection
