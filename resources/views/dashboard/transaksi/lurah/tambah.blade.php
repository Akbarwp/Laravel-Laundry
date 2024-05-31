@extends("dashboard.layouts.main")

@section("js")
    <script>
        $(document).ready(function() {
            let number = 1;
            $('#addLayanan').click(function (e) {
                e.preventDefault();
                $("#layananCart").append(`
                    <div class="w-full flex flex-wrap justify-center items-center gap-2 lg:flex-nowrap">
                        <label class="form-control w-full lg:w-1/4">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Jenis Pakaian</span>
                            </div>
                            <select id="jenisPakaian`+number+`" name="jenis_pakaian_id[]" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" onchange="return ubahJenisPakaian(this.value, 'jenisLayanan`+number+`', 'hargaJenisLayanan`+number+`');" required>
                                <option disabled selected>Pilih Pakaian!</option>
                                @foreach ($pakaian as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="form-control w-full lg:w-1/4">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Jenis Layanan</span>
                            </div>
                            <select id="jenisLayanan`+number+`" name="jenis_layanan_id[]" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" onchange="return ubahJenisLayanan(document.getElementById('jenisPakaian`+number+`').value, $(this).val(), 'harga_jenis_layanan_id`+number+`');" multiple required>
                            <option disabled>Pilih Layanan!</option>
                            </select>
                        </label>
                        <label class="form-control w-full lg:w-1/4">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Harga Jenis Layanan</span>
                            </div>
                            <input type="text" id="harga_jenis_layanan_id`+number+`" name="harga_jenis_layanan_id[]" placeholder="Harga Jenis Layanan" class="input input-bordered join-item w-full text-blue-700 dark:bg-slate-100" readonly required />
                        </label>
                        <label class="form-control w-full lg:w-1/4">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Total Pakaian</span>
                            </div>
                            <div class="join">
                                <input type="number" value="1" min="1" id="total_pakaian`+number+`" name="total_pakaian[]" placeholder="Total Pakaian" class="input input-bordered join-item w-full text-blue-700 dark:bg-slate-100" required />
                                <div class="btn btn-active join-item rounded-r-full">Kg</div>
                            </div>
                        </label>
                    </div>
                `);
                number++;
            });

            $("#saveLayanan").click(function (e) {
                e.preventDefault();

                // let pakaian = [];
                // pakaian = $('select[name="jenis_pakaian_id[]"]').map(function () {
                //     return $(this).val();
                // }).get();
                // console.log(pakaian);

                // let jmlTransaksi = document.getElementById('layananCart').children.length;
                // let layanan = [];
                // for (let i = 1; i <= jmlTransaksi; i++) {
                //     layanan[i] = $("select[name='jenis_layanan_id[]'][id='jenisLayanan"+i+"']").val();
                // }
                // console.log(layanan);

                let hargaLayanan = [];
                hargaLayanan = $('input[name="harga_jenis_layanan_id[]"]').map(function () {
                    return $(this).val();
                }).get();
                // console.log(hargaLayanan);

                let totalPakaian = [];
                totalPakaian = $('input[name="total_pakaian[]"]').map(function () {
                    return $(this).val();
                }).get();
                // console.log(totalPakaian);

                let layananPrioritas = $("select[name='layanan_prioritas_id']").val();
                // console.log(layananPrioritas);

                totalBiaya(hargaLayanan, totalPakaian, layananPrioritas);
            });

            $("#deleteLayanan").click(function (e) {
                e.preventDefault();
                let layananCart = document.getElementById("layananCart");
                if (layananCart.hasChildNodes()) {
                    layananCart.removeChild(layananCart.lastElementChild);
                } else {
                    console.log("empty");
                }
                number--;
            });

            $("input[name='bayar']").keyup(function (e) {
                let totalBayar = $("input[name='total_bayar_akhir']").val();
                $("input[name='kembalian']").val(this.value-totalBayar);
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

        function ubahJenisPakaian(jenisPakaianId, namaIdjenisLayanan, namaIdHargaJenisLayanan) {
            $.ajax({
                type: "get",
                url: "{{ route('transaksi.lurah.cabang.create.ubahJenisPakaian', $cabang->slug) }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "jenisPakaianId": jenisPakaianId
                },
                success: function(data) {
                    // console.log(data);
                    $("select[name='jenis_layanan_id[]'][id='"+namaIdjenisLayanan+"'] option").remove();
                    $("select[name='jenis_layanan_id[]'][id='"+namaIdjenisLayanan+"']").append(`<option disabled selected>Pilih Layanan!</option>`);
                    $.each(data, function(key, val) {
                        $("select[name='jenis_layanan_id[]'][id='"+namaIdjenisLayanan+"']").append(`
                            <option value="` + val.id + `">` + val.nama + `</option>
                        `);
                    });
                    $("select[name='harga_jenis_layanan_id[]'][id='"+namaIdHargaJenisLayanan+"'] option").remove();
                    $("select[name='harga_jenis_layanan_id[]'][id='"+namaIdHargaJenisLayanan+"']").append(`<option disabled selected>Harga Jenis Layanan</option>`);
                }
            });
        }
        function ubahJenisLayanan(jenisPakaianId, jenisLayananId, namaIdHargaJenisLayanan) {
            $.ajax({
                type: "get",
                url: "{{ route('transaksi.lurah.cabang.create.ubahJenisLayanan', $cabang->slug) }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "jenisPakaianId": jenisPakaianId,
                    "jenisLayananId": jenisLayananId
                },
                success: function(data) {
                    // console.log(data);
                    // $("select[name='harga_jenis_layanan_id[]'][id='"+namaIdHargaJenisLayanan+"'] option").remove();
                    // let items = [];
                    // $.each(data, function(key, val) {
                    //     items.push(val);
                    // });

                    // let harga = new Intl.NumberFormat("id-ID", {
                    //     style: "currency",
                    //     currency: "IDR"
                    // }).format(data);

                    // $("select[name='harga_jenis_layanan_id[]'][id='"+namaIdHargaJenisLayanan+"']").append(`
                    //     <option value="` + items[0] + `">` + harga + `</option>
                    // `);
                    $("input[name='harga_jenis_layanan_id[]'][id='"+namaIdHargaJenisLayanan+"']").val(data);
                }
            });
        }

        function totalBiaya(hargaLayanan, totalPakaian, layananPrioritas) {
            $.ajax({
                type: "get",
                url: "{{ route('transaksi.lurah.cabang.create.hitungTotalBayar', $cabang->slug) }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "hargaLayanan": hargaLayanan,
                    "totalPakaian": totalPakaian,
                    "layananPrioritas": layananPrioritas
                },
                success: function(data) {
                    $('input[name="total_biaya_layanan"]').val(data[0]);
                    $('input[name="total_biaya_prioritas"]').val(data[1]);
                    $('input[name="total_bayar_akhir"]').val(data[2]);

                    $('input[name="bayar"]').val(0);
                    $('input[name="kembalian"]').val(0);
                }
            });
        }

        function storeTransaksiCabang() {
            if (parseInt($('input[name="kembalian"]').val()) < 0 || $('input[name="bayar"]').val() <= 0) {
                return Swal.fire({
                    title: 'Gagal',
                    text: 'Uang yang dibayarkan kurang',
                    icon: 'error',
                    confirmButtonColor: '#6419E6',
                    confirmButtonText: 'OK',
                });
            }

            let pakaian = [];
            pakaian = $('select[name="jenis_pakaian_id[]"]').map(function () {
                return $(this).val();
            }).get();

            let jmlTransaksi = document.getElementById('layananCart').children.length;
            let layanan = [];
            for (let i = 1; i <= jmlTransaksi; i++) {
                layanan[i-1] = $("select[name='jenis_layanan_id[]'][id='jenisLayanan"+i+"']").val();
            }

            let hargaJenisLayanan = [];
            hargaJenisLayanan = $('input[name="harga_jenis_layanan_id[]"]').map(function () {
                return $(this).val();
            }).get();

            let totalPakaian = [];
            totalPakaian = $('input[name="total_pakaian[]"]').map(function () {
                return $(this).val();
            }).get();

            $.ajax({
                type: "post",
                url: "{{ route('transaksi.lurah.cabang.store', $cabang->slug) }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "pelanggan_id": $("select[name='pelanggan_id']").val(),
                    "gamis_id": $("select[name='gamis_id']").val(),
                    "total_biaya_layanan": $("input[name='total_biaya_layanan']").val(),
                    "total_biaya_prioritas": $("input[name='total_biaya_prioritas']").val(),
                    "total_bayar_akhir": $("input[name='total_bayar_akhir']").val(),
                    "jenis_pembayaran": $("input[name='jenis_pembayaran']").val(),
                    "bayar": $("input[name='bayar']").val(),
                    "kembalian": $("input[name='kembalian']").val(),
                    "layanan_prioritas_id": $("select[name='layanan_prioritas_id']").val(),
                    "jenis_pakaian_id": pakaian,
                    "jenis_layanan_id": layanan,
                    "harga_jenis_layanan_id": hargaJenisLayanan,
                    "total_pakaian": totalPakaian
                },
                success: function(data) {
                    Swal.fire({
                        title: "Berhasil",
                        text: "Transaksi Berhasil Ditambahkan",
                        icon: "success",
                        confirmButtonColor: '#6419E6',
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if ({{ $isJadwal }}) {
                                return window.location.href = "{{ route('transaksi.lurah.cabang.jadwal', $cabang->slug) }}";
                            }
                            return window.location.href = "{{ route('transaksi.lurah.cabang', $cabang->slug) }}";
                        }
                    });
                },
                error: function(data) {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Transaksi gagal ditambahkan',
                        icon: 'error',
                        confirmButtonColor: '#6419E6',
                        confirmButtonText: 'OK',
                    });
                }
            });
        }
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Form Tambah --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div class="mb-3">
                        <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                        <h6 class="font-bold dark:text-white">Cabang: <span class="text-blue-500">{{ $cabang->nama }}</span></h6>
                    </div>
                    <div>
                        @if ($isJadwal)
                            <a href="{{ route("transaksi.lurah.cabang.jadwal", $cabang->slug) }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                <i class="ri-arrow-left-line"></i>
                                Kembali
                            </a>
                        @else
                            <a href="{{ route("transaksi.lurah.cabang", $cabang->slug) }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                <i class="ri-arrow-left-line"></i>
                                Kembali
                            </a>
                        @endif
                    </div>
                </div>
                <div class="flex-auto px-6 pb-6 pt-0">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">
                                        Pelanggan |
                                        <a href="{{ route('pelanggan') }}" class="link link-primary dark:link-accent">Sudah membuat daftar pelanggan?</a>
                                    </span>
                                </div>
                                <select name="pelanggan_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                    <option disabled selected>Pilih Pelanggan!</option>
                                    @foreach ($pelanggan as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                @error("pelanggan_id")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Gamis</span>
                                </div>
                                <select name="gamis_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100">
                                    <option disabled selected>Pilih Gamis!</option>
                                    @foreach ($gamis as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                @error("gamis_id")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                        </div>
                        <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Total Biaya Layanan</span>
                                </div>
                                <input type="number" min="0" name="total_biaya_layanan" placeholder="Total Biaya Layanan" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly required />
                                @error("total_biaya_layanan")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Total Biaya Prioritas</span>
                                </div>
                                <input type="number" min="0" value="0" name="total_biaya_prioritas" placeholder="Total Biaya Prioritas" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly required />
                                @error("total_biaya_prioritas")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                        </div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Total Bayar</span>
                            </div>
                            <input type="number" min="0" name="total_bayar_akhir" placeholder="Total Bayar" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly required />
                            @error("total_bayar_akhir")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Jenis Pembayaran</span>
                            </div>
                            <input type="text" name="jenis_pembayaran" placeholder="Jenis Pembayaran" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" required />
                            @error("jenis_pembayaran")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Bayar</span>
                                </div>
                                <input type="number" min="0" name="bayar" placeholder="Bayar" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" required />
                                @error("bayar")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Kembalian</span>
                                </div>
                                <input type="text" name="kembalian" placeholder="Kembalian" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly required />
                                @error("kembalian")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                        </div>

                        <div>
                            <div class="my-3">
                                <h6 class="font-bold dark:text-white">Detail Transaksi</h6>
                            </div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Layanan Prioritas</span>
                                </div>
                                <select name="layanan_prioritas_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                    @foreach ($layananPrioritas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }} (Rp{{ number_format($item->harga, 2, ',', '.') }}/kg)</option>
                                    @endforeach
                                </select>
                                @error("layanan_prioritas_id")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <div class="w-full">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Aksi Layanan</span>
                                </div>
                                <button type="button" id="addLayanan" class="btn btn-warning tooltip btn-sm w-10 h-10 text-white" data-tip="Tambah Layanan">
                                    <i class="ri-add-fill text-base"></i>
                                </button>
                                <button type="button" id="deleteLayanan" class="btn btn-error tooltip btn-sm w-10 h-10 text-white" data-tip="Hapus Layanan (*paling bawah)">
                                    <i class="ri-delete-bin-line text-base"></i>
                                </button>
                                <button type="button" id="saveLayanan" class="btn btn-info btn-sm tooltip w-10 h-10 text-white" data-tip="Cek Total Bayar">
                                    <i class="ri-save-3-line text-base"></i>
                                </button>
                            </div>
                            <div id="layananCart"></div>
                        </div>

                        <div class="my-5 flex flex-wrap justify-center gap-2">
                            <button onclick="return storeTransaksiCabang()" type="button" class="btn btn-success w-full max-w-md text-white">Tambah Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Akhir Form Tambah --}}
        </div>
    </div>
@endsection
