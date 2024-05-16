@extends('dashboard.layouts.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endsection

@section('js')
    {{-- <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                    responsive: false,
                    order: [],
                    pagingType: 'full_numbers',
                })
                .columns.adjust()
                .responsive.recalc();
        });
        setTimeout(function() {
            document.getElementById('alert').style.display = 'none';
        }, 3000);

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus Data!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route("user.hapus2") }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
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
    </script> --}}

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
    </script>
@endsection

@section('container')
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Tabel Cabang --}}
            @role('lurah')
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Daftar Cabang</h6>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable1" class="nowrap stripe mb-0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="bg-blue-500 rounded-tl text-xs font-bold uppercase text-white dark:text-white">
                                            Nama
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Lokasi
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Created_at
                                        </th>
                                        <th class="bg-blue-500 rounded-tr text-xs font-bold uppercase text-white dark:text-white">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cabang as $item)
                                        <tr>
                                            <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->lokasi }}
                                                </p>
                                            </td>
                                            <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                                </p>
                                            </td>
                                            <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                                <div>
                                                    <a href="{{ route('user.cabang', $item->slug) }}" class="btn btn-outline btn-info btn-sm mb-1">
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
                        <a href="#" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
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
                                    <th class="bg-blue-500 rounded-tl text-xs font-bold uppercase text-white dark:text-white">
                                        Username
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Email
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
                                    <th class="bg-blue-500 rounded-tr text-xs font-bold uppercase text-white dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $user->username }}
                                            </p>
                                        </td>
                                        <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $user->email }}
                                            </p>
                                        </td>
                                        <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $user->roles->pluck('name')->first() }}
                                            </p>
                                        </td>
                                        <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}
                                            </p>
                                        </td>
                                        <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $user->cabang ? $user->cabang->nama : '-' }}
                                            </p>
                                        </td>
                                        <td class="bg-transparent text-left align-middle border-b border-slate-600">
                                            <div>
                                                <a href="#" class="btn btn-outline btn-warning btn-xs">
                                                    <i class="ri-pencil-fill text-base"></i>
                                                </a>
                                                <button type="button" onclick="return delete_button('{{ $user->id }}', '{{ $user->name }}');" class="btn btn-outline btn-error btn-xs">
                                                    <i class="ri-delete-bin-line text-base"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel User --}}
        </div>
    </div>
@endsection
