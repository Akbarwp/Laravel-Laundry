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

        function show_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);
            $("#loading_edit4").html(loading);
            $("#loading_edit5").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('pelanggan.show') }}",
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
                    $("input[name='telepon']").val(items[3]);
                    $("textarea[name='alamat']").val(items[4]);
                    $("input[name='cabang_id']").val(items[8]);

                    if (items[2] == "L") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", true);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", false);
                    } else if (items[2] == "P") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", false);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", true);
                    }

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                    $("#loading_edit4").html(loading);
                    $("#loading_edit5").html(loading);
                }
            });
        }

        function edit_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);
            $("#loading_edit4").html(loading);
            $("#loading_edit5").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('pelanggan.edit') }}",
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
                    $("input[name='telepon']").val(items[3]);
                    $("textarea[name='alamat']").val(items[4]);

                    if (items[2] == "L") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", true);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", false);
                    } else if (items[2] == "P") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", false);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", true);
                    }

                    $("option[value='" + items[5] + "']").prop("selected", true);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                    $("#loading_edit4").html(loading);
                    $("#loading_edit5").html(loading);
                }
            });
        }

        function delete_button(id, nama) {
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
                        url: "{{ route('pelanggan.delete') }}",
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
                        <form action="{{ route('pelanggan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Cabang</span>
                                </div>
                                <select name="cabang_id" class="select select-bordered w-full text-blue-700" readonly required>
                                    <option value="{{ $cabang->id }}" selected>{{ $cabang->nama }}</option>
                                </select>
                                @error('cabang_id')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nama</span>
                                </div>
                                <input type="text" name="nama" placeholder="Nama" class="input input-bordered w-full text-blue-700" value="{{ old('nama') }}" required />
                                @error('nama')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <div class="mt-3 w-full max-w-md">
                                <div class="label">
                                    <span class="label-text font-semibold">Jenis Kelamin</span>
                                </div>
                                <div class="rounded-lg border border-slate-300 px-3 py-2">
                                    <div class="form-control">
                                        <label class="label cursor-pointer">
                                            <span class="label-text text-blue-700">Laki-laki</span>
                                            <input type="radio" value="L" name="jenis_kelamin" class="radio-primary radio" required />
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer">
                                            <span class="label-text text-blue-700">Perempuan</span>
                                            <input type="radio" value="P" name="jenis_kelamin" class="radio-primary radio" required />
                                        </label>
                                    </div>
                                </div>
                                @error("jenis_kelamin")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Telepon</span>
                                </div>
                                <input type="text" name="telepon" placeholder="Telepon" class="input input-bordered w-full text-blue-700" value="{{ old('telepon') }}" required />
                                @error('telepon')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Alamat</span>
                                </div>
                                <textarea name="alamat" placeholder="Alamat" class="textarea textarea-bordered w-full text-base text-blue-500">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
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
                                <span class="label-text font-semibold">Cabang</span>
                                <span class="label-text-alt" id="loading_edit5"></span>
                            </div>
                            <input type="text" name="cabang_id" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Nama</span>
                                <span class="label-text-alt" id="loading_edit1"></span>
                            </div>
                            <input type="text" name="nama" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <div class="mt-3 w-full max-w-md">
                            <div class="label">
                                <span class="label-text font-semibold">Jenis Kelamin</span>
                                <span class="label-text-alt" id="loading_edit2"></span>
                            </div>
                            <div class="rounded-lg border border-slate-300 px-3 py-2">
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700">Laki-laki</span>
                                        <input type="radio" value="L" name="jenis_kelamin" class="radio-primary radio" disabled />
                                    </label>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700">Perempuan</span>
                                        <input type="radio" value="P" name="jenis_kelamin" class="radio-primary radio" disabled />
                                    </label>
                                </div>
                            </div>
                        </div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Telepon</span>
                                <span class="label-text-alt" id="loading_edit3"></span>
                            </div>
                            <input type="text" name="telepon" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Alamat</span>
                                <span class="label-text-alt" id="loading_edit4"></span>
                            </div>
                            <textarea name="alamat" class="textarea textarea-bordered w-full text-base text-blue-500" readonly></textarea>
                        </label>
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
                        <form action="{{ route('pelanggan.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" hidden>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Cabang</span>
                                    <span class="label-text-alt" id="loading_edit5"></span>
                                </div>
                                <select name="cabang_id" class="select select-bordered w-full text-blue-700" readonly required>
                                    <option value="{{ $cabang->id }}" selected>{{ $cabang->nama }}</option>
                                </select>
                                @error('cabang_id')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nama</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <input type="text" name="nama" placeholder="Nama" class="input input-bordered w-full text-blue-700" required />
                                @error('nama')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <div class="mt-3 w-full max-w-md">
                                <div class="label">
                                    <span class="label-text font-semibold">Jenis Kelamin</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <div class="rounded-lg border border-slate-300 px-3 py-2">
                                    <div class="form-control">
                                        <label class="label cursor-pointer">
                                            <span class="label-text text-blue-700">Laki-laki</span>
                                            <input type="radio" value="L" name="jenis_kelamin" class="radio-primary radio" required />
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer">
                                            <span class="label-text text-blue-700">Perempuan</span>
                                            <input type="radio" value="P" name="jenis_kelamin" class="radio-primary radio" required />
                                        </label>
                                    </div>
                                </div>
                                @error("jenis_kelamin")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Telepon</span>
                                </div>
                                <input type="text" name="telepon" placeholder="Telepon" class="input input-bordered w-full text-blue-700" required />
                                @error('telepon')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Alamat</span>
                                </div>
                                <textarea name="alamat" placeholder="Alamat" class="textarea textarea-bordered w-full text-base text-blue-500"></textarea>
                                @error('alamat')
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

            {{-- Awal Tabel Pelanggan --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div class="mb-3">
                        <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                        <h6 class="font-bold dark:text-white">Cabang: <span class="text-blue-500">{{ $cabang->nama }}</span></h6>
                    </div>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
                        <a href="{{ route('pelanggan') }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                            <i class="ri-arrow-left-line"></i>
                            Kembali
                        </a>
                        <label for="create_modal" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                            <i class="ri-add-fill"></i>
                            Tambah
                        </label>
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
                                        Jenis Kelamin
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Telepon
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
                                @foreach ($pelanggan as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->jenis_kelamin }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->telepon }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->id }}')">
                                                    <i class="ri-eye-line text-base"></i>
                                                </label>
                                                <label for="edit_button" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button('{{ $item->id }}')">
                                                    <i class="ri-pencil-fill text-base"></i>
                                                </label>
                                                <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->id }}', '{{ $item->nama }}')">
                                                    <i class="ri-delete-bin-line text-base"></i>
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
            {{-- Akhir Tabel Pelanggan --}}
        </div>
    </div>
@endsection
