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

        function show_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('jenis-layanan.show') }}",
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

                    $("input[name='nama']").val(items[1]);
                    $("textarea[name='deskripsi']").val(items[2]);
                    if (items[3]) {
                        $("input[name='for_gamis'][value='1']").prop("checked", true);
                        $("input[name='for_gamis'][value='0']").prop("checked", false);
                    } else {
                        $("input[name='for_gamis'][value='1']").prop("checked", false);
                        $("input[name='for_gamis'][value='0']").prop("checked", true);
                    }

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                }
            });
        }

        function edit_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('jenis-layanan.edit') }}",
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
                    $("input[name='nama']").val(items[1]);
                    $("textarea[name='deskripsi']").val(items[2]);
                    if (items[3]) {
                        $("input[name='for_gamis'][value='1']").prop("checked", true);
                        $("input[name='for_gamis'][value='0']").prop("checked", false);
                    } else {
                        $("input[name='for_gamis'][value='1']").prop("checked", false);
                        $("input[name='for_gamis'][value='0']").prop("checked", true);
                    }

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                }
            });
        }

        function delete_button(id, cabang_id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan masuk ke dalam Trash!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
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
                        url: "{{ route('jenis-layanan.delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "cabang_id": cabang_id
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

        function restore_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan dipulihkan!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Pulihkan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('jenis-layanan.restore') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Data berhasil dipulihkan!',
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
                                title: 'Data gagal dipulihkan!',
                            })
                        }
                    });
                }
            })
        }

        function destroy_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data yang dihapus permanen tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus Permanen',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('jenis-layanan.destroy') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Data berhasil dihapus permanen!',
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
                                title: 'Data gagal dihapus permanen!',
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection

@section('container')
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Modal Create --}}
            <input type="checkbox" id="create_modal" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    <div class="mb-3 flex justify-between">
                        <h3 class="text-lg font-bold">Tambah {{ $title }}</h3>
                        <label for="create_modal" class="cursor-pointer">
                            <i class="ri-close-large-fill"></i>
                        </label>
                    </div>
                    <div>
                        <form action="{{ route('jenis-layanan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                        <x-label-input-required :value="'Nama Layanan'" />
                                    </span>
                                </div>
                                <input type="text" name="nama" placeholder="Nama Layanan" class="input input-bordered w-full text-blue-700" value="{{ old('nama') }}" required />
                                @error('nama')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Deskripsi</span>
                                </div>
                                <textarea name="deskripsi" placeholder="Deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <div class="mt-3 w-full max-w-md">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                        <x-label-input-required :value="'Untuk Gamis'" />
                                    </span>
                                </div>
                                <div class="rounded-lg border border-slate-300 px-3 py-2">
                                    <div class="form-control">
                                        <label class="label cursor-pointer">
                                            <span class="label-text text-blue-700">Iya</span>
                                            <input type="radio" value="1" name="for_gamis" class="radio-primary radio" required />
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer">
                                            <span class="label-text text-blue-700">Tidak</span>
                                            <input type="radio" value="0" name="for_gamis" class="radio-primary radio" required />
                                        </label>
                                    </div>
                                </div>
                                @error("for_gamis")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success mt-3 w-full text-white">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal Create --}}

            {{-- Awal Modal Show --}}
            <input type="checkbox" id="show_button" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    <div class="mb-3 flex justify-between">
                        <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                        <label for="show_button" class="cursor-pointer">
                            <i class="ri-close-large-fill"></i>
                        </label>
                    </div>
                    <div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Nama Layanan</span>
                                <span class="label-text-alt" id="loading_edit1"></span>
                            </div>
                            <input type="text" name="nama" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Deskripsi</span>
                                <span class="label-text-alt" id="loading_edit2"></span>
                            </div>
                            <textarea name="deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500" readonly></textarea>
                        </label>
                        <div class="mt-3 w-full max-w-md">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Untuk Gamis</span>
                                <span class="label-text-alt" id="loading_edit3"></span>
                            </div>
                            <div class="rounded-lg border border-slate-300 px-3 py-2">
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700">Iya</span>
                                        <input type="radio" value="1" name="for_gamis" class="radio-primary radio" disabled />
                                    </label>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700">Tidak</span>
                                        <input type="radio" value="0" name="for_gamis" class="radio-primary radio" disabled />
                                    </label>
                                </div>
                            </div>
                            @error("for_gamis")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal Show --}}

            {{-- Awal Modal Edit --}}
            <input type="checkbox" id="edit_button" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    <div class="mb-3 flex justify-between">
                        <h3 class="text-lg font-bold">Ubah {{ $title }}</h3>
                        <label for="edit_button" class="cursor-pointer">
                            <i class="ri-close-large-fill"></i>
                        </label>
                    </div>
                    <div>
                        <form action="{{ route('jenis-layanan.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" hidden>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                        <x-label-input-required :value="'Nama Layanan'" />
                                    </span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <input type="text" name="nama" placeholder="Nama Layanan" class="input input-bordered w-full text-blue-700" required />
                                @error('nama')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Deskripsi</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <textarea name="deskripsi" placeholder="Deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500"></textarea>
                                @error('deskripsi')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <div class="mt-3 w-full max-w-md">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                        <x-label-input-required :value="'Untuk Gamis'" />
                                    </span>
                                    <span class="label-text-alt" id="loading_edit3"></span>
                                </div>
                                <div class="rounded-lg border border-slate-300 px-3 py-2">
                                    <div class="form-control">
                                        <label class="label cursor-pointer">
                                            <span class="label-text text-blue-700">Iya</span>
                                            <input type="radio" value="1" name="for_gamis" class="radio-primary radio" required />
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer">
                                            <span class="label-text text-blue-700">Tidak</span>
                                            <input type="radio" value="0" name="for_gamis" class="radio-primary radio" required />
                                        </label>
                                    </div>
                                </div>
                                @error("for_gamis")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal Edit --}}

            {{-- Awal Modal Impor --}}
                <input type="checkbox" id="impor_modal" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Impor Data</h3>
                            <label for="impor_modal" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('jenis-layanan.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-control w-full">
                                    <input type="file" name="impor" placeholder="Impor Data" class="file-input file-input-bordered w-full text-blue-700" required />
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Impor</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Impor --}}

            {{-- Awal Tabel Jenis Layanan --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
                        @if (!$cabang->deleted_at)
                            <label for="create_modal" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                <i class="ri-add-fill"></i>
                                Tambah
                            </label>
                            <label for="impor_modal" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                <i class="ri-upload-2-line"></i>
                                Impor
                            </label>
                            <form action="{{ route('jenis-layanan.export') }}" method="GET" enctype="multipart/form-data" class="inline-block">
                                @csrf
                                <label class="form-control w-full">
                                    <input type="text" name="cabang" value="{{ auth()->user()->cabang->slug }}" hidden readonly />
                                </label>
                                <button type="submit" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                    <i class="ri-download-2-line"></i>
                                    Ekspor
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Nama Layanan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Untuk Gamis
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Created_at
                                    </th>
                                    <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jenisLayanan as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            @if ($item->for_gamis)
                                                <div class="badge badge-success text-white">Iya</div>
                                            @else
                                                    <div class="badge badge-error text-white">Tidak</div>
                                            @endif
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->id }}')">
                                                    <i class="ri-eye-line text-base"></i>
                                                </label>
                                                @if (!$cabang->deleted_at)
                                                    <label for="edit_button" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button('{{ $item->id }}')">
                                                        <i class="ri-pencil-fill text-base"></i>
                                                    </label>
                                                    <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                        <i class="ri-delete-bin-line text-base"></i>
                                                    </label>
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
            {{-- Akhir Tabel Jenis Layanan --}}

            {{-- Awal Tabel Jenis Layanan Trash --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }} Trash <span class="text-error">(data yang telah dihapus)</span></h6>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable1" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Nama Layanan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Untuk Gamis
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Created_at
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        deleted_at
                                    </th>
                                    <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jenisLayananTrash as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            @if ($item->for_gamis)
                                                <div class="badge badge-success text-white">Iya</div>
                                            @else
                                                <div class="badge badge-error text-white">Tidak</div>
                                            @endif
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ Carbon\Carbon::parse($item->deleted_at)->translatedFormat('d F Y H:i:s') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->id }}')">
                                                    <i class="ri-eye-line text-base"></i>
                                                </label>
                                                @if (!$cabang->deleted_at)
                                                    <label for="restore_button" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button('{{ $item->id }}', '{{ $item->nama }}')">
                                                        <i class="ri-history-line text-base"></i>
                                                    </label>
                                                    <label for="destroy_button" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button('{{ $item->id }}', '{{ $item->nama }}')">
                                                        Hapus Permanen
                                                    </label>
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
            {{-- Akhir Tabel Jenis Layanan Trash --}}
        </div>
    </div>
@endsection
