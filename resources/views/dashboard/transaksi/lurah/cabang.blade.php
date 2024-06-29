@extends("dashboard.layouts.main")

@section("js")
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
        });

        @if (session()->has("success"))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has("error"))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session("error") }}',
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

        function delete_button(transaksi_id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dipulihkan kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('transaksi.lurah.cabang.delete', $cabang->slug) }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "transaksi_id": transaksi_id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Data berhasil dihapus!',
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
                                title: 'Data gagal dihapus!',
                            })
                        }
                    });
                }
            })
        }

        function edit_status_button(transaksi_id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('transaksi.lurah.cabang.edit.status', $cabang->slug) }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "transaksi_id": transaksi_id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("select[name='status'] option[value='"+items[1]+"']").prop("selected", true);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading)
                }
            });
        }

        function konfirmasi_upah_button(transaksi_id, gamis, tanggal, konfirmasi) {
            let judul;
            let judulSukses;
            let judulGagal;
            if (!konfirmasi) {
                judul = 'Konfirmasi Pemberian Upah ke '+gamis+' ?';
                judulSukses = 'Konfirmasi berhasil dilakukan';
                judulGagal = 'Konfirmasi gagal dilakukan';
            } else {
                judul = 'Pembatalan Pemberian Upah ke '+gamis+' ?';
                judulSukses = 'Pembatalan berhasil dilakukan';
                judulGagal = 'Pembatalan gagal dilakukan';
            }

            Swal.fire({
                title: judul,
                text: tanggal,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('transaksi.lurah.cabang.konfirmasiUpahButton', $cabang->slug) }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "transaksi_id": transaksi_id,
                            "konfirmasi": konfirmasi
                        },
                        success: function(response) {
                            Swal.fire({
                                title: judulSukses,
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
                                title: judulGagal,
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Tabel Transaksi --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
                        <a href="{{ route('transaksi.lurah') }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                            <i class="ri-arrow-left-line"></i>
                            Kembali
                        </a>
                        @if (!$cabang->deleted_at)
                            <a href="{{ route("transaksi.lurah.cabang.create", ['cabang' => $cabang->slug, 'isJadwal' => $isJadwal]) }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                <i class="ri-add-fill"></i>
                                Tambah
                            </a>
                        @endif
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
                                    <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $value => $item)
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
                                                @if ($item->pegawai->roles[0]->name == 'manajer_laundry')
                                                    {{ $item->pegawai->manajer[0]->nama }}
                                                @elseif ($item->pegawai->roles[0]->name == 'pegawai_laundry')
                                                    {{ $item->pegawai->pegawai[0]->nama }}
                                                @elseif ($item->pegawai->roles[0]->name == 'lurah')
                                                    {{ $item->pegawai->lurah[0]->nama }}
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
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                <a href="{{ route("transaksi.lurah.view", ['cabang' => $cabang->slug, 'transaksi' => $item->id, 'isJadwal' => $isJadwal]) }}" class="btn btn-outline btn-info btn-sm">
                                                    <i class="ri-eye-line text-base"></i>
                                                </a>
                                                @if (!$cabang->deleted_at)
                                                    <a href="{{ route("transaksi.lurah.cabang.edit", ['cabang' => $cabang->slug, 'transaksi' => $item->id, 'isJadwal' => $isJadwal]) }}" class="btn btn-outline btn-warning btn-sm">
                                                        <i class="ri-pencil-fill text-base"></i>
                                                    </a>
                                                    <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->id }}')">
                                                        <i class="ri-delete-bin-line text-base"></i>
                                                    </label>
                                                    <label for="edit_status_button" class="btn btn-outline btn-primary tooltip btn-sm" data-tip="Ubah Status" onclick="return edit_status_button('{{ $item->id }}')">
                                                        <i class="ri-draft-line text-base"></i>
                                                    </label>
                                                @endif
                                                <a href="{{ route("transaksi.cetak-struk", ['transaksi' => $item->id]) }}" target="_blank" class="btn btn-outline btn-ghost dark:border-white dark:text-white dark:bg-transparent dark:hover:bg-white dark:hover:text-slate-700 btn-sm tooltip" data-tip="Cetak Struk">
                                                    <i class="ri-receipt-line text-base"></i>
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
            {{-- Akhir Tabel Transaksi --}}

            {{-- Awal Modal Edit --}}
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
                        <form action="{{ route('transaksi.lurah.cabang.update.status', ['cabang' => $cabang->slug, 'isJadwal' => $isJadwal]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" hidden>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Status</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <select name="status" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                    @foreach ($status as $item)
                                        <option value="{{ $item->value }}">{{ $item->value }}</option>
                                    @endforeach
                                </select>
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
            {{-- Akhir Modal Edit --}}

            {{-- Awal Tabel Upah Gamis --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">Upah Gamis</h6>
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
                                        Upah
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Pelanggan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Total Bayar
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Tanggal
                                    </th>
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
                                                Rp{{ number_format($item->upah_gamis + $item->total_biaya_layanan_tambahan, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->pelanggan->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->total_bayar_akhir, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                @if (!$cabang->deleted_at)
                                                    @if (!$item->konfirmasi_upah_gamis)
                                                        <label for="konfirmasi_upah_button" class="btn btn-outline btn-primary btn-sm tooltip" data-tip="Konfirmasi Upah Gamis" onclick="return konfirmasi_upah_button('{{ $item->transaksi_id }}', '{{ $item->nama_gamis }}', '{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}', {{ $item->konfirmasi_upah_gamis }})">
                                                            <i class="ri-receipt-line text-base"></i>
                                                        </label>
                                                    @else
                                                        <label for="konfirmasi_upah_button" class="btn btn-outline btn-error btn-sm tooltip" data-tip="Pembatalan Upah Gamis" onclick="return konfirmasi_upah_button('{{ $item->transaksi_id }}', '{{ $item->nama_gamis }}', '{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}', {{ $item->konfirmasi_upah_gamis }})">
                                                            <i class="ri-close-line text-base"></i>
                                                        </label>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Upah Gamis --}}
        </div>
    </div>
@endsection
