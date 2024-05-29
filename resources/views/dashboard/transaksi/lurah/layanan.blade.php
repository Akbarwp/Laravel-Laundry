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
        });
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Detail Transaksi --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div class="mb-3">
                        <h6 class="font-bold dark:text-white">Detail Transaksi</h6>
                        <h6 class="font-bold dark:text-white">Cabang: <span class="text-blue-500">{{ $cabang->nama }}</span></h6>
                    </div>
                    <div>
                        <a href="{{ route("transaksi.lurah.view", ['cabang' => $cabang->slug, 'transaksi' => $transaksi->id]) }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                            <i class="ri-arrow-left-line"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="flex-auto px-6 pb-6 pt-0">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Total Pakaian</span>
                        </div>
                        <input type="text" name="total_pakaian" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $detailTransaksi->total_pakaian }} {{ $detailTransaksi->jenis_satuan }}" readonly />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Harga Layanan Akhir</span>
                        </div>
                        <input type="text" name="harga_layanan_akhir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $detailTransaksi->harga_layanan_akhir ? number_format($detailTransaksi->harga_layanan_akhir, 2, ',', '.') : '-' }}" readonly />
                    </label>
                    <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Total Biaya Layanan</span>
                            </div>
                            <input type="text" name="total_biaya_layanan" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $detailTransaksi->total_biaya_layanan ? number_format($detailTransaksi->total_biaya_layanan, 2, ',', '.') : '-' }}" readonly />
                        </label>
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Total Biaya Prioritas</span>
                            </div>
                            <input type="text" name="total_biaya_prioritas" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $detailTransaksi->total_biaya_prioritas ? number_format($detailTransaksi->total_biaya_prioritas, 2, ',', '.') : '-' }}" readonly />
                        </label>
                    </div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Total Bayar</span>
                        </div>
                        <input type="text" name="total_bayar_akhir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="Rp{{ $transaksi->total_biaya_layanan && $detailTransaksi->total_biaya_prioritas ? number_format($detailTransaksi->total_biaya_layanan+$detailTransaksi->total_biaya_prioritas, 2, ',', '.') : '-' }}" readonly />
                    </label>
                </div>
            </div>
            {{-- Akhir Detail Transaksi --}}

            {{-- Awal Tabel Detail Layanan Transaksi --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">Detail Layanan Transaksi</h6>
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
                                        Harga
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Jenis Satuan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailLayananTransaksi as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->hargaJenisLayanan->jenisPakaian->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->hargaJenisLayanan->jenisLayanan->nama }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->hargaJenisLayanan->harga, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->hargaJenisLayanan->jenis_satuan }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Detail Layanan Transaksi --}}
        </div>
    </div>
@endsection
