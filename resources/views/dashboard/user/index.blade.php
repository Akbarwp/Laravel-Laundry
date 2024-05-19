@extends("dashboard.layouts.main")

@section("css")
    <link rel="stylesheet" href="{{ asset("css/datatable.css") }}">
@endsection

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

            $('#myTable2').DataTable({
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

        function delete_button(slug, nama) {
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
                        url: "{{ route('user.delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "slug": slug
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

        function restore_button(slug, nama) {
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
                        url: "{{ route('user.restore') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "slug": slug
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

        function destroy_button(slug, nama) {
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
                        url: "{{ route('user.destroy') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "slug": slug
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

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Tabel Cabang --}}
            @role("lurah")
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Daftar Cabang</h6>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable1" class="nowrap stripe mb-0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Lokasi
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
                                    @foreach ($cabang as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->lokasi }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <a href="{{ route("user.cabang", $item->slug) }}" class="btn btn-outline btn-info btn-sm mb-1">
                                                        <i class="ri-id-card-line text-base"></i>
                                                        Detail User
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
            @endrole
            {{-- Akhir Tabel Cabang --}}

            {{-- Awal Tabel User --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
                        <a href="{{ route("user.create") }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                            <i class="ri-add-fill"></i>
                            Tambah
                        </a>
                    </div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Nama
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Email
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Telepon
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Role
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Created_at
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Cabang
                                    </th>
                                    <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @role(["lurah", "manajer_laundry"])
                                    @role("lurah")
                                        @foreach ($lurah as $item)
                                            <tr>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->nama }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->email }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->telepon }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->roles->pluck("name")->first() }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->cabang ? $item->user->cabang->nama : "-" }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <div>
                                                        <a href="{{ route("user.view", $item->slug) }}" class="btn btn-outline btn-info btn-sm">
                                                            <i class="ri-eye-line text-base"></i>
                                                        </a>
                                                        <a href="{{ route("user.edit", $item->slug) }}" class="btn btn-outline btn-warning btn-sm">
                                                            <i class="ri-pencil-fill text-base"></i>
                                                        </a>
                                                        <a href="{{ route("user.edit.password", $item->slug) }}" class="btn btn-outline tooltip btn-primary btn-sm" data-tip="Ganti Password">
                                                            <i class="ri-lock-password-line text-base"></i>
                                                        </a>
                                                        <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                            <i class="ri-delete-bin-line text-base"></i>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @foreach ($rw as $item)
                                            <tr>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->nama }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->email }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->telepon }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->roles->pluck("name")->first() }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->cabang ? $item->user->cabang->nama : "-" }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <div>
                                                        <a href="{{ route("user.view", $item->slug) }}" class="btn btn-outline btn-info btn-sm">
                                                            <i class="ri-eye-line text-base"></i>
                                                        </a>
                                                        <a href="{{ route("user.edit", $item->slug) }}" class="btn btn-outline btn-warning btn-sm">
                                                            <i class="ri-pencil-fill text-base"></i>
                                                        </a>
                                                        <a href="{{ route("user.edit.password", $item->slug) }}" class="btn btn-outline tooltip btn-primary btn-sm" data-tip="Ganti Password">
                                                            <i class="ri-lock-password-line text-base"></i>
                                                        </a>
                                                        <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                            <i class="ri-delete-bin-line text-base"></i>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @foreach ($manajer as $item)
                                            <tr>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->nama }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->email }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->telepon }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->roles->pluck("name")->first() }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->user->cabang ? $item->user->cabang->nama : "-" }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <div>
                                                        <a href="{{ route("user.view", $item->slug) }}" class="btn btn-outline btn-info btn-sm">
                                                            <i class="ri-eye-line text-base"></i>
                                                        </a>
                                                        <a href="{{ route("user.edit", $item->slug) }}" class="btn btn-outline btn-warning btn-sm">
                                                            <i class="ri-pencil-fill text-base"></i>
                                                        </a>
                                                        <a href="{{ route("user.edit.password", $item->slug) }}" class="btn btn-outline tooltip btn-primary btn-sm" data-tip="Ganti Password">
                                                            <i class="ri-lock-password-line text-base"></i>
                                                        </a>
                                                        <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                            <i class="ri-delete-bin-line text-base"></i>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endrole

                                    @foreach ($pegawai as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->user->email }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->telepon }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->user->roles->pluck("name")->first() }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->user->cabang ? $item->user->cabang->nama : "-" }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <a href="{{ route("user.view", $item->slug) }}" class="btn btn-outline btn-info btn-sm">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </a>
                                                    <a href="{{ route("user.edit", $item->slug) }}" class="btn btn-outline btn-warning btn-sm">
                                                        <i class="ri-pencil-fill text-base"></i>
                                                    </a>
                                                    <a href="{{ route("user.edit.password", $item->slug) }}" class="btn btn-outline tooltip btn-primary btn-sm" data-tip="Ganti Password">
                                                        <i class="ri-lock-password-line text-base"></i>
                                                    </a>
                                                    <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                        <i class="ri-delete-bin-line text-base"></i>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach ($gamis as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->user->email }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->telepon }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->user->roles->pluck("name")->first() }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->user->cabang ? $item->user->cabang->nama : "-" }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <a href="{{ route("user.view", $item->slug) }}" class="btn btn-outline btn-info btn-sm">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </a>
                                                    <a href="{{ route("user.edit", $item->slug) }}" class="btn btn-outline btn-warning btn-sm">
                                                        <i class="ri-pencil-fill text-base"></i>
                                                    </a>
                                                    <a href="{{ route("user.edit.password", $item->slug) }}" class="btn btn-outline tooltip btn-primary btn-sm" data-tip="Ganti Password">
                                                        <i class="ri-lock-password-line text-base"></i>
                                                    </a>
                                                    <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                        <i class="ri-delete-bin-line text-base"></i>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endrole
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel User --}}

            @role(["lurah", "manajer_laundry"])
                {{-- Awal Tabel User Trash --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">{{ $title }} Trash <span class="text-error">(data yang telah dihapus)</span></h6>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable2" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Email
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Telepon
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Role
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Created_at
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Deleted_at
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Cabang
                                        </th>
                                        <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @role(["lurah", "manajer_laundry"])
                                        @role("lurah")
                                            @foreach ($lurahTrash as $item)
                                                <tr>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->nama }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->user->email }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->telepon }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->user->roles->pluck("name")->first() }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ Carbon\Carbon::parse($item->deleted_at)->translatedFormat("d F Y H:i:s") }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->user->cabang ? $item->user->cabang->nama : "-" }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <div>
                                                            <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->slug }}', 'trash')">
                                                                <i class="ri-eye-line text-base"></i>
                                                            </label>
                                                            <label for="restore_button" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @foreach ($rwTrash as $item)
                                                <tr>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->nama }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->user->email }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->telepon }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->user->roles->pluck("name")->first() }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ Carbon\Carbon::parse($item->deleted_at)->translatedFormat("d F Y H:i:s") }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->user->cabang ? $item->user->cabang->nama : "-" }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <div>
                                                            <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->slug }}', 'trash')">
                                                                <i class="ri-eye-line text-base"></i>
                                                            </label>
                                                            <label for="restore_button" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @foreach ($manajerTrash as $item)
                                                <tr>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->nama }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->email }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->telepon }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->roles->pluck("name")->first() }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ Carbon\Carbon::parse($item->deleted_at)->translatedFormat("d F Y H:i:s") }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                            {{ $item->nama_cabang ? $item->nama_cabang : "-" }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                        <div>
                                                            <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->slug }}', 'trash')">
                                                                <i class="ri-eye-line text-base"></i>
                                                            </label>
                                                            <label for="restore_button" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endrole

                                        @foreach ($pegawaiTrash as $item)
                                            <tr>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->nama }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->email }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->telepon }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->roles->pluck("name")->first() }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ Carbon\Carbon::parse($item->deleted_at)->translatedFormat("d F Y H:i:s") }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->nama_cabang ? $item->nama_cabang : "-" }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <div>
                                                        <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->slug }}', 'trash')">
                                                                <i class="ri-eye-line text-base"></i>
                                                            </label>
                                                            <label for="restore_button" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @foreach ($gamisTrash as $item)
                                            <tr>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->nama }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->email }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->telepon }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->roles->pluck("name")->first() }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ Carbon\Carbon::parse($item->deleted_at)->translatedFormat("d F Y H:i:s") }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                        {{ $item->nama_cabang ? $item->nama_cabang : "-" }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                    <div>
                                                        <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->slug }}', 'trash')">
                                                                <i class="ri-eye-line text-base"></i>
                                                            </label>
                                                            <label for="restore_button" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button('{{ $item->slug }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endrole
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Akhir Tabel User Trash --}}
            @endrole
        </div>
    </div>
@endsection
