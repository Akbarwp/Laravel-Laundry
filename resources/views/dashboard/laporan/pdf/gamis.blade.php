@extends('dashboard.laporan.layouts.main')

@section('tanggal')
    <p style="padding-bottom: 0px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::parse($tanggalAwal)->format('F Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('F Y') }}</span></p>
    <p style="padding-bottom: 20px">Cabang: <span style="font-weight: 500">{{ $nama_cabang ? $nama_cabang->nama : 'Semua Cabang' }}</span></p>
@endsection

@section('tabel')
    <table>
        <thead>
            <tr>
                <th>Gamis</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Total Transaksi Dikerjakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $value => $item)
                <tr>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            {{ $item->nama_gamis }}
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
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <span>Total:</span>
                    <span style="font-weight: 500">{{ $transaksi->count('total_transaksi') }} transaksi</span>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
