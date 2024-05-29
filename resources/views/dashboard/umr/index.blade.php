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

            $('#close_show_button').click(function() {
                $("#is_used_show").removeClass('bg-success');
                $("#is_used_show").removeClass('bg-error');
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

            $.ajax({
                type: "get",
                url: "{{ route('umr.show') }}",
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

                    let upah = new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR"
                    }).format(items[2]);

                    $("input[name='regional']").val(items[1]);
                    $("input[name='upah']").val(upah);
                    $("input[name='tahun']").val(items[3]);
                    if (items[4] == 1) {
                        $("#is_used_show").html(`
                            <i class="ri-check-fill"></i>
                        `);
                        $("#is_used_show").addClass('bg-success');
                    } else if (items[4] == 0) {
                        $("#is_used_show").html(`
                            <i class="ri-close-fill"></i>
                        `);
                        $("#is_used_show").addClass('bg-error');
                    }

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                    $("#loading_edit4").html(loading);
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

            $.ajax({
                type: "get",
                url: "{{ route('umr.edit') }}",
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
                    $("input[name='regional']").val(items[1]);
                    $("input[name='upah']").val(items[2]);
                    $("input[name='tahun']").val(items[3]);
                    if (items[4] == 1) {
                        $("input[name='is_used'][value='1']").prop('checked', true);
                    } else if (items[4] == 0) {
                        $("input[name='is_used'][value='0']").prop('checked', true);
                    }

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                    $("#loading_edit4").html(loading);
                }
            });
        }

        function delete_button(id, regional, tahun) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data yang dihapus tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<div class='flex flex-col'>" +
                    "<b>Regional: " + regional + "</b>" +
                    "<b>Tahun: " + tahun + "</b>" +
                    "</div>",
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
                        url: "{{ route('umr.delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
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
                                title: 'Gagal',
                                text: response.responseJSON.message
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
                        <form action="{{ route('umr.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Regional</span>
                                </div>
                                <input type="text" name="regional" placeholder="Regional" class="input input-bordered w-full text-blue-700" value="{{ old('regional') }}" required />
                                @error('regional')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Upah</span>
                                </div>
                                <input type="number" min="0" step="0.01" name="upah" placeholder="Upah" class="input input-bordered w-full text-blue-700" value="{{ old('upah') }}" required />
                                @error('upah')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Tahun</span>
                                </div>
                                <input type="number" min="0" name="tahun" placeholder="Tahun" class="input input-bordered w-full text-blue-700" value="{{ old('tahun') }}" required />
                                @error('tahun')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <div class="mt-3 w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Is_Used?</span>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Iya</span>
                                        <input type="radio" value="1" name="is_used" class="radio radio-primary" />
                                    </label>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Tidak</span>
                                        <input type="radio" value="0" name="is_used" class="radio radio-primary" checked />
                                    </label>
                                </div>
                                @error('is_used')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
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
                        <label for="show_button" id="close_show_button" class="cursor-pointer">
                            <i class="ri-close-large-fill"></i>
                        </label>
                    </div>
                    <div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Regional</span>
                                <span class="label-text-alt" id="loading_edit1"></span>
                            </div>
                            <input type="text" name="regional" placeholder="Regional" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Upah</span>
                                <span class="label-text-alt" id="loading_edit2"></span>
                            </div>
                            <input type="text" name="upah" placeholder="Upah" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Tahun</span>
                                <span class="label-text-alt" id="loading_edit3"></span>
                            </div>
                            <input type="number" min="0" name="tahun" placeholder="Tahun" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <div class="mt-3 w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Is_Used?</span>
                                <span class="label-text-alt" id="loading_edit4"></span>
                            </div>
                            <div id="is_used_show" class="ml-3 flex h-5 w-5 items-center justify-center rounded-full text-white"></div>
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
                        <form action="{{ route('umr.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" hidden>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Regional</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <input type="text" name="regional" placeholder="Regional" class="input input-bordered w-full text-blue-700" required />
                                @error('regional')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Upah</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <input type="number" min="0" step="0.01" name="upah" placeholder="Upah" class="input input-bordered w-full text-blue-700" required />
                                @error('upah')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Tahun</span>
                                    <span class="label-text-alt" id="loading_edit3"></span>
                                </div>
                                <input type="number" min="0" name="tahun" placeholder="Tahun" class="input input-bordered w-full text-blue-700" required />
                                @error('tahun')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <div class="mt-3 w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Is_Used?</span>
                                    <span class="label-text-alt" id="loading_edit4"></span>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Iya</span>
                                        <input type="radio" value="1" name="is_used" class="radio radio-primary" required />
                                    </label>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Tidak</span>
                                        <input type="radio" value="0" name="is_used" class="radio radio-primary" required />
                                    </label>
                                </div>
                                @error('is_used')
                                    <div class="label">
                                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal Edit --}}

            {{-- Awal Tabel UMR --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
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
                                        Regional
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Upah
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Tahun
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Is_Used
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
                                @foreach ($umr as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->regional }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->upah, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->tahun }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                @if ($item->is_used)
                                                    <div class="bg-success flex h-5 w-5 items-center justify-center rounded-full text-white">
                                                        <i class="ri-check-line"></i>
                                                    </div>
                                                @else
                                                    <div class="bg-error flex h-5 w-5 items-center justify-center rounded-full text-white">
                                                        <i class="ri-close-line"></i>
                                                    </div>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                <label for="show_button" class="btn btn-outline btn-info btn-sm" onclick="return show_button('{{ $item->id }}', 'tabel')">
                                                    <i class="ri-eye-line text-base"></i>
                                                </label>
                                                <label for="edit_button" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button('{{ $item->id }}')">
                                                    <i class="ri-pencil-fill text-base"></i>
                                                </label>
                                                <label for="delete_button" class="btn btn-outline btn-error btn-sm" onclick="return delete_button('{{ $item->id }}', '{{ $item->regional }}', '{{ $item->tahun }}')">
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
            {{-- Akhir Tabel UMR --}}
        </div>
    </div>
@endsection
