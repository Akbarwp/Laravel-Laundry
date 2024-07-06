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
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Transaksi --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div class="mb-3">
                        <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                    </div>
                    <div>
                        @if ($isHarian)
                            <a href="{{ route("transaksi-gamis") }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                <i class="ri-arrow-left-line"></i>
                                Kembali
                            </a>
                        @else
                            <a href="{{ route("transaksi-gamis.semua") }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                <i class="ri-arrow-left-line"></i>
                                Kembali
                            </a>
                        @endif
                    </div>
                </div>
                <div class="flex-auto px-6 pb-6 pt-0">
                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Status</span>
                        </div>
                        <x-kolom-status-transaksi :value="$transaksi->status" />
                    </label>
                    <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Nota Layanan</span>
                            </div>
                            <input type="text" name="nota_layanan" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $transaksi->nota_layanan }}" readonly />
                        </label>
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Nota Pelanggan</span>
                            </div>
                            <input type="text" name="nota_pelanggan" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $transaksi->nota_pelanggan }}" readonly />
                        </label>
                    </div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Waktu</span>
                        </div>
                        <input type="datetime" name="waktu" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ \Carbon\Carbon::parse($transaksi->waktu)->format("d F Y H:i:s") }}" readonly />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Layanan Prioritas</span>
                        </div>
                        <input type="text" name="layanan_prioritas_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $transaksi->layananPrioritas->nama }} (Rp{{ number_format($transaksi->layananPrioritas->harga, 2, ',', '.') }}/kg)" readonly />
                    </label>
                    <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Total Biaya Layanan</span>
                            </div>
                            <input type="text" name="total_biaya_layanan" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $transaksi->total_biaya_layanan ? number_format($transaksi->total_biaya_layanan, 2, ',', '.') : '-' }}" readonly />
                        </label>
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Total Biaya Prioritas</span>
                            </div>
                            <input type="text" name="total_biaya_prioritas" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $transaksi->total_biaya_prioritas ? number_format($transaksi->total_biaya_prioritas, 2, ',', '.') : '-' }}" readonly />
                        </label>
                    </div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Total Bayar</span>
                        </div>
                        <input type="text" name="total_bayar_akhir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $transaksi->total_bayar_akhir ? number_format($transaksi->total_bayar_akhir, 2, ',', '.') : '-' }}" readonly />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Jenis Pembayaran</span>
                        </div>
                        <input type="text" name="jenis_pembayaran" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $transaksi->jenis_pembayaran }}" readonly />
                    </label>
                    <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Bayar</span>
                            </div>
                            <input type="text" name="bayar" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $transaksi->bayar ? number_format($transaksi->bayar, 2, ',', '.') : '-' }}" readonly />
                        </label>
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Kembalian</span>
                            </div>
                            <input type="text" name="kembalian" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $transaksi->kembalian ? number_format($transaksi->kembalian, 2, ',', '.') : '-' }}" readonly />
                        </label>
                    </div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Pelanggan</span>
                        </div>
                        <input type="text" name="pegawai_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $transaksi->pelanggan->nama }}" readonly />
                    </label>
                    <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Pegawai</span>
                            </div>
                            <input type="text" name="pegawai_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly
                                @if ($transaksi->pegawai->roles[0]->name == 'manajer_laundry')
                                    value="{{ $transaksi->pegawai->manajer[0]->nama }}"
                                @elseif ($transaksi->pegawai->roles[0]->name == 'pegawai_laundry')
                                    value="{{ $transaksi->pegawai->pegawai[0]->nama }}"
                                @elseif ($transaksi->pegawai->roles[0]->name == 'lurah')
                                    value="{{ $transaksi->pegawai->lurah[0]->nama }}"
                                @endif
                            />
                        </label>
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Gamis</span>
                            </div>
                            <input type="text" name="gamis_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $transaksi->gamis_id ? $transaksi->gamis->nama : '-' }}" readonly />
                        </label>
                    </div>
                </div>
            </div>
            {{-- Akhir Transaksi --}}

            {{-- Awal Tabel Detail Transaksi --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">Detail Transaksi</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right"></div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Jenis Pakaian
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Jenis Layanan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Total Pakaian
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Harga Layanan Akhir
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Total Biaya Layanan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Total Biaya Prioritas
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Total Bayar
                                    </th>
                                    <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailTransaksi as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->detailLayananTransaksi[0]->hargaJenisLayanan->jenisPakaian->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                @foreach ($item->detailLayananTransaksi as $layanan)
                                                    {{ $layanan->hargaJenisLayanan->jenisLayanan->nama }};
                                                @endforeach
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->total_pakaian }} {{ $item->jenis_satuan }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->harga_layanan_akhir, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->total_biaya_layanan, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->total_biaya_prioritas, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->total_biaya_layanan+$item->total_biaya_prioritas, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <div>
                                                <a href="{{ route("transaksi-gamis.view.layanan", ['transaksi' => $item->transaksi_id, 'detailTransaksi' => $item->id]) }}" class="btn btn-outline btn-info btn-sm">
                                                    <i class="ri-eye-line text-base"></i>
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
            {{-- Akhir Tabel Detail Transaksi --}}

            {{-- Awal Tabel Layanan Tambahan Transaksi --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">Detail Transaksi</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right"></div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable1" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Layanan Tambahan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Harga
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($layananTambahanTransaksi as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->layananTambahan->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->layananTambahan->harga, 2, ',', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Layanan Tambahan Transaksi --}}
        </div>
    </div>
@endsection
