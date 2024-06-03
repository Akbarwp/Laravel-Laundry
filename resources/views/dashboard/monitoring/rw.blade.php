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
    </script>
@endsection

@section('container')
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Ekspor PDF --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">Eksport PDF</h6>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <form action="{{ route('monitoring.rw.pdf') }}" method="post" enctype="multipart/form-data" target="_blank">
                            @csrf
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Tanggal Awal</span>
                                    </div>
                                    <input type="month" name="tanggalAwal" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ \Carbon\Carbon::now()->format('Y-') . \Carbon\Carbon::now()->format('m') }}" />
                                </label>
                                <label class="form-control w-full lg:w-1/2">
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

            {{-- Awal Tabel Monitoring Gamis --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <form action="{{ route('monitoring.rw') }}" method="get" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Tanggal Awal</span>
                                    </div>
                                    <input type="month" name="tanggalAwal" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ \Carbon\Carbon::now()->format('Y-') . \Carbon\Carbon::now()->format('m') }}" />
                                </label>
                                <label class="form-control w-full lg:w-1/2">
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
                                        Gamis
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Upah
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Status
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Bulan
                                    </th>
                                    <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                        Tahun
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monitoring as $item)
                                    <tr>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                {{ $item->nama_gamis }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                Rp{{ number_format($item->upah, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                            <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                @if ($item->status == 'Gamis')
                                                    <span class="badge badge-primary">{{ $item->status }}</span>
                                                @elseif ($item->status == 'Lulus')
                                                    <span class="badge badge-accent">{{ $item->status }}</span>
                                                @endif
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div>
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Status: <span class="text-primary">Gamis</span></span>
                                    </div>
                                    <input type="text" name="total_pendapatan_laundry" value="{{ $monitoring->where('status', 'Gamis')->count() }} orang" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly />
                                </label>
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100 text-lg">Status: <span class="text-success">Lulus</span></span>
                                    </div>
                                    <input type="text" name="total_pendapatan_laundry" value="{{ $monitoring->where('status', 'Lulus')->count() }} orang" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly />
                                </label>
                            </div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100 text-lg">Total Pendapatan Gamis</span>
                                </div>
                                <input type="text" name="total_pendapatan_laundry" value="Rp{{ number_format($monitoring->sum('upah'), 2, ',', '.') }}" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" readonly />
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Monitoring Gamis --}}
        </div>
    </div>
@endsection
