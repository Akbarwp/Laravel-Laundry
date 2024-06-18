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
                searching: false,
            });
        });
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Ekspor PDF --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">Eksport PDF</h6>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <form action="{{ route('laporan.pelanggan.pdf') }}" method="post" enctype="multipart/form-data" target="_blank">
                            @csrf
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                @role('lurah')
                                    <label class="form-control w-full lg:w-1/3">
                                        <div class="label">
                                            <span class="label-text font-semibold dark:text-slate-100 text-lg">Cabang</span>
                                        </div>
                                        <select name="cabang_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100">
                                            <option disabled selected>Pilih Cabang!</option>
                                            @foreach ($cabang as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                @endrole
                                <label class="form-control w-full @if(auth()->user()->hasRole('lurah'))lg:w-1/3 @else lg:w-1/2 @endif">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Tanggal Awal</span>
                                    </div>
                                    <input type="month" name="tanggalAwal" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ \Carbon\Carbon::now()->format('Y-') . \Carbon\Carbon::now()->format('m') }}" />
                                </label>
                                <label class="form-control w-full @if(auth()->user()->hasRole('lurah'))lg:w-1/3 @else lg:w-1/2 @endif">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Tanggal Akhir</span>
                                    </div>
                                    <input type="month" name="tanggalAkhir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ \Carbon\Carbon::now()->format('Y-m') }}" />
                                </label>
                            </div>
                            <button type="submit" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mt-3 inline-block cursor-pointer rounded-lg border border-solid border-red-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-red-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                <i class="ri-file-pdf-2-line text-base"></i>
                                Ekspor PDF
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Akhir Ekspor PDF --}}

            {{-- Awal Tabel Laporan Pelanggan --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <form action="{{ route('laporan.pelanggan') }}" method="get" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                @role('lurah')
                                    <label class="form-control w-full lg:w-1/3">
                                        <div class="label">
                                            <span class="label-text font-semibold dark:text-slate-100 text-lg">Cabang</span>
                                        </div>
                                        <select name="cabang_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100">
                                            <option disabled selected>Pilih Cabang!</option>
                                            @foreach ($cabang as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                @endrole
                                <label class="form-control w-full @if(auth()->user()->hasRole('lurah'))lg:w-1/3 @else lg:w-1/2 @endif">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Tanggal Awal</span>
                                    </div>
                                    <input type="month" name="tanggalAwal" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ \Carbon\Carbon::now()->format('Y-') . \Carbon\Carbon::now()->format('m') }}" />
                                </label>
                                <label class="form-control w-full @if(auth()->user()->hasRole('lurah'))lg:w-1/3 @else lg:w-1/2 @endif">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Tanggal Akhir</span>
                                    </div>
                                    <input type="month" name="tanggalAkhir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ \Carbon\Carbon::now()->format('Y-m') }}" />
                                </label>
                            </div>
                            <button type="submit" class="btn btn-accent btn-sm uppercase mt-3">Filter</button>
                        </form>

                        <table id="myTable" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Pelanggan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Bulan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Tahun
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Total Transaksi
                                    </th>
                                    <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Total Pengeluaran
                                    </th>
                                    @role('lurah')
                                        <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Cabang
                                        </th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $value => $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->nama_pelanggan }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->bulan }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->tahun }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->total_transaksi }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->total_pengeluaran, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        @role('lurah')
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama_cabang }}
                                                </p>
                                            </td>
                                        @endrole
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div>
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100 text-lg">Cabang: <span class="text-blue-500">{{ $nama_cabang ? $nama_cabang->nama : 'Semua Cabang' }}</span></span>
                            </div>
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Total Transaksi</span>
                                    </div>
                                    <input type="text" name="total_transaksi" value="{{ $transaksi->sum('total_transaksi') }}" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly />
                                </label>
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Total Pengeluaran Pelanggan</span>
                                    </div>
                                    <input type="text" name="total_pengeluaran" value="Rp{{ number_format($transaksi->sum('total_pengeluaran'), 2, ',', '.') }}" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Laporan Pelanggan --}}
        </div>
    </div>
@endsection
