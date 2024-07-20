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
                <th>Status</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Upah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $item)
                <tr>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            {{ $item->nama_gamis }}
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
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            Rp{{ number_format($item->upah, 2, ',', '.') }}
                        </p>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td></td>
                <td>
                    <span>
                        Status (Gamis):
                    </span>
                    <span style="font-weight: 500">
                        {{ $transaksi->where('status', 'Gamis')->count() }} orang
                    </span>
                </td>
                <td></td>
                <td></td>
                <td>
                    <div>Total Pendapatan Gamis:</div>
                    <div style="font-weight: 500">Rp{{ number_format($transaksi->sum('upah'), 2, ',', '.') }}</div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <span>
                        Status (Lulus):
                    </span>
                    <span style="font-weight: 500">
                        {{ $transaksi->where('status', 'Lulus')->count() }} orang
                    </span>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
