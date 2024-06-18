@extends('dashboard.laporan.layouts.main')

@section('tanggal')
    <p style="padding-bottom: 20px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</span></p>
@endsection

@section('tabel')
    <table>
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Total Transaksi</th>
                <th>Total Pengeluaran</th>
                @role('lurah')
                    <th>Cabang</th>
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
            <tr>
                <td colspan="5" style="font-weight: 500">Cabang: {{ $nama_cabang ? $nama_cabang->nama : 'Semua Cabang' }}</td>
            </tr>
            <tr>
                <td colspan="5" style="font-weight: 500">Total Transaksi: {{ $transaksi->sum('total_transaksi') }}</td>
            </tr>
            <tr>
                <td colspan="5" style="font-weight: 500">Total Pengeluaran Pelanggan: Rp{{ number_format($transaksi->sum('total_pengeluaran'), 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
@endsection
