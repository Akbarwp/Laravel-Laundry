@extends('dashboard.layouts.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endsection

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

        function edit_status_button(monitoring_id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('monitoring.edit.status') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "monitoring_id": monitoring_id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='status'][value='"+items[1]+"']").prop("checked", true);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading)
                }
            });
        }
    </script>
@endsection

@section('container')
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Modal Edit Status --}}
            <input type="checkbox" id="edit_status_button" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    <div class="mb-3 flex justify-between">
                        <h3 class="text-lg font-bold">Ubah Status Transaksi</h3>
                        <label for="edit_status_button" class="cursor-pointer">
                            <i class="ri-close-large-fill"></i>
                        </label>
                    </div>
                    <div>
                        <form action="{{ route('monitoring.update.status') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" hidden>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Status</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Gamis</span>
                                        <input type="radio" value="Gamis" name="status" class="radio radio-primary" required />
                                    </label>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Lulus</span>
                                        <input type="radio" value="Lulus" name="status" class="radio radio-primary" required />
                                    </label>
                                </div>
                                @error("status")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui Status</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal Edit Status --}}

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
                            Perbarui Data
                        </button>
                        <button class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2" onclick="return resetMonitoringGamis()">
                            <i class="ri-loop-right-line"></i>
                            Reset Data
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
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Tahun
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
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                <label for="edit_status_button" class="btn btn-outline btn-primary tooltip btn-sm" data-tip="Ubah Status" onclick="return edit_status_button('{{ $item->id }}')">
                                                    <i class="ri-draft-line text-base"></i>
                                                </label>
                                            </div>
                                        </td>
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
