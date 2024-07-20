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
            $('#myTable3').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                    },
                },
                order: [],
                pagingType: 'full_numbers',
            });
            $('#myTable4').DataTable({
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

        // Jenis Layanan
        function show_button_jenis_layanan(id) {
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

        function restore_button_jenis_layanan(id, cabang_id, nama) {
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
                            "id": id,
                            "cabang_id": cabang_id
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

        function destroy_button_jenis_layanan(id, cabang_id, nama) {
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
                            "id": id,
                            "cabang_id": cabang_id
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

        // Layanan Tambahan
        function show_button_layanan_tambahan(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('layanan-tambahan.show') }}",
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
                    $("input[name='harga']").val(items[2]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }

        function restore_button_layanan_tambahan(id, cabang_id, nama) {
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
                        url: "{{ route('layanan-tambahan.restore') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "cabang_id": cabang_id
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

        function destroy_button_layanan_tambahan(id, cabang_id, nama) {
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
                        url: "{{ route('layanan-tambahan.destroy') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "cabang_id": cabang_id
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

        // Jenis Pakaian
        function show_button_jenis_pakaian(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('jenis-pakaian.show') }}",
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

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }

        function restore_button_jenis_pakaian(id, cabang_id, nama) {
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
                        url: "{{ route('jenis-pakaian.restore') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "cabang_id": cabang_id
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

        function destroy_button_jenis_pakaian(id, cabang_id, nama) {
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
                        url: "{{ route('jenis-pakaian.destroy') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "cabang_id": cabang_id
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

        // Harga Jenis Layanan
        function show_button_harga_jenis_layanan(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);
            $("#loading_edit4").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('harga-jenis-layanan.show') }}",
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

                    $("input[name='harga']").val(items[1]);
                    $("input[name='jenis_satuan']").val(items[2]);
                    $("input[name='jenis_layanan_id']").val(items[9]);
                    $("input[name='jenis_pakaian_id']").val(items[10]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                    $("#loading_edit4").html(loading);
                }
            });
        }

        function restore_button_harga_jenis_layanan(id, layanan, pakaian) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan dipulihkan!</p>" +
                    "<div class='divider'></div>" +
                    "<p class='font-bold'>Layanan: " + layanan + "</p>" +
                    "<p class='font-bold'>Pakaian: " + pakaian + "</p>",
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
                        url: "{{ route('harga-jenis-layanan.restore') }}",
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

        function destroy_button_harga_jenis_layanan(id, layanan, pakaian) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data yang dihapus permanen tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<p class='font-bold'>Layanan: " + layanan + "</p>" +
                    "<p class='font-bold'>Pakaian: " + pakaian + "</p>",
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
                        url: "{{ route('harga-jenis-layanan.destroy') }}",
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

        // Layanan Prioritas
        function show_button_layanan_prioritas(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit4").html(loading);
            $("#loading_edit5").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('layanan-prioritas.show') }}",
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
                    $("input[name='harga']").val(items[3]);
                    $("input[name='prioritas']").val(items[4]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit4").html(loading);
                    $("#loading_edit5").html(loading);
                }
            });
        }

        function restore_button_layanan_prioritas(id, nama) {
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
                        url: "{{ route('layanan-prioritas.restore') }}",
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

        function destroy_button_layanan_prioritas(id, nama) {
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
                        url: "{{ route('layanan-prioritas.destroy') }}",
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

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6">
                    <h6 class="text-xl font-bold text-blue-700 dark:text-white">{{ $cabang->nama }}</h6>
                    <a href="{{ route('layanan-cabang.cabang', $cabang->slug) }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                        <i class="ri-arrow-left-line"></i>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Awal Jenis Layanan --}}
                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_jenis_layanan" class="cursor-pointer">
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
                                    <span class="label-text font-semibold">Untuk Gamis</span>
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

                {{-- Awal Tabel Jenis Layanan Trash --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Jenis Layanan Trash <span class="text-error">(data yang telah dihapus)</span></h6>
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
                                                    <label for="show_button_jenis_layanan" class="btn btn-outline btn-info btn-sm" onclick="return show_button_jenis_layanan('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("pic")
                                                            <label for="restore_button_jenis_layanan" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button_jenis_layanan('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button_jenis_layanan" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button_jenis_layanan('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                        @endrole
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
            {{-- Akhir Jenis Layanan --}}

            {{-- Awal Jenis Pakaian --}}
                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_jenis_pakaian" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_jenis_pakaian" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nama Pakaian</span>
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
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Tabel Jenis Pakaian Trash --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Jenis Pakaian Trash <span class="text-error">(data yang telah dihapus)</span></h6>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable1" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama Pakaian
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
                                    @foreach ($jenisPakaianTrash as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
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
                                                    <label for="show_button_jenis_pakaian" class="btn btn-outline btn-info btn-sm" onclick="return show_button_jenis_pakaian('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("pic")
                                                            <label for="restore_button_jenis_pakaian" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button_jenis_pakaian('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button_jenis_pakaian" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button_jenis_pakaian('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                        @endrole
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
                {{-- Akhir Tabel Jenis Pakaian Trash --}}
            {{-- Akhir Jenis Pakaian --}}

            {{-- Awal Harga Jenis Layanan --}}
                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_harga_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_harga_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold">Jenis Layanan</span>
                                        <span class="label-text-alt" id="loading_edit1"></span>
                                    </div>
                                    <input type="text" name="jenis_layanan_id" class="input input-bordered w-full text-blue-700" readonly />
                                </label>
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold">Jenis Pakaian</span>
                                        <span class="label-text-alt" id="loading_edit2"></span>
                                    </div>
                                    <input type="text" name="jenis_pakaian_id" class="input input-bordered w-full text-blue-700" readonly />
                                </label>
                            </div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Harga</span>
                                    <span class="label-text-alt" id="loading_edit3"></span>
                                </div>
                                <input type="number" name="harga" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Jenis Satuan</span>
                                    <span class="label-text-alt" id="loading_edit4"></span>
                                </div>
                                <input type="text" name="jenis_satuan" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Tabel Harga Jenis Layanan Trash --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Harga Jenis Layanan Trash <span class="text-error">(data yang telah dihapus)</span></h6>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable2" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Jenis Layanan
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Jenis Pakaian
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Harga
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Jenis Satuan
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Created_at
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Deleted_at
                                        </th>
                                        <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hargaJenisLayananTrash as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama_layanan }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama_pakaian }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    Rp{{ number_format($item->harga, 2, ',', '.') }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->jenis_satuan }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->deleted_at)->translatedFormat('d F Y H:i:s') }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <label for="show_button_harga_jenis_layanan" class="btn btn-outline btn-info btn-sm" onclick="return show_button_harga_jenis_layanan('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("pic")
                                                            <label for="restore_button_harga_jenis_layanan" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button_harga_jenis_layanan('{{ $item->id }}', '{{ $item->nama_layanan }}', '{{ $item->nama_pakaian }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button_harga_jenis_layanan" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button_harga_jenis_layanan('{{ $item->id }}', '{{ $item->nama_layanan }}', '{{ $item->nama_pakaian }}')">
                                                                Hapus Permanen
                                                            </label>
                                                        @endrole
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
                {{-- Akhir Tabel Harga Jenis Layanan Trash --}}
            {{-- Akhir Harga Jenis Layanan --}}

            {{-- Awal Layanan Prioritas --}}
                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_layanan_prioritas" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_layanan_prioritas" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nama Layanan Prioritas</span>
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
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Harga</span>
                                    <span class="label-text-alt" id="loading_edit4"></span>
                                </div>
                                <input type="number" name="harga" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nilai Prioritas</span>
                                    <span class="label-text-alt" id="loading_edit5"></span>
                                </div>
                                <input type="number" name="prioritas" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Tabel Layanan Prioritas Trash --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Layanan Prioritas Trash <span class="text-error">(data yang telah dihapus)</span></h6>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable3" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama Layanan Prioritas
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Harga
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nilai Prioritas
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
                                    @foreach ($layananPrioritasTrash as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    Rp{{ number_format($item->harga, 2, ',', '.') }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->prioritas }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <label for="show_button_layanan_prioritas" class="btn btn-outline btn-info btn-sm" onclick="return show_button_layanan_prioritas('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("pic")
                                                            <label for="restore_button_layanan_prioritas" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button_layanan_prioritas('{{ $item->id }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button_layanan_prioritas" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button_layanan_prioritas('{{ $item->id }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                        @endrole
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
                {{-- Akhir Tabel Layanan Prioritas Trash --}}
            {{-- Akhir Layanan Prioritas --}}

            {{-- Awal Layanan Tambahan --}}
                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_layanan_tambahan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_layanan_tambahan" class="cursor-pointer">
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
                                    <span class="label-text font-semibold">Harga</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <input type="number" min="0" step="0.01" name="harga" placeholder="Harga" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Tabel Layanan Tambahan Trash --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Layanan Tambahan Trash <span class="text-error">(data yang telah dihapus)</span></h6>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable4" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama Layanan
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Harga
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
                                    @foreach ($layananTambahanTrash as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                Rp{{ number_format($item->harga, 2, ',', '.') }}
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
                                                    <label for="show_button_layanan_tambahan" class="btn btn-outline btn-info btn-sm" onclick="return show_button_layanan_tambahan('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("pic")
                                                            <label for="restore_button_layanan_tambahan" class="btn btn-outline btn-primary btn-sm" onclick="return restore_button_layanan_tambahan('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                <i class="ri-history-line text-base"></i>
                                                            </label>
                                                            <label for="destroy_button_layanan_tambahan" class="btn btn-outline btn-error btn-sm" onclick="return destroy_button_layanan_tambahan('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                Hapus Permanen
                                                            </label>
                                                        @endrole
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
                {{-- Akhir Tabel Layanan Tambahan Trash --}}
            {{-- Akhir Layanan Tambahan --}}
        </div>
    </div>
@endsection
