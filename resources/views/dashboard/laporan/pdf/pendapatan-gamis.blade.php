@extends('dashboard.laporan.layouts.main')

@section('tanggal')
    <p style="padding-bottom: 20px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</span></p>
@endsection

@section('tabel')
    <table>
        <thead>
            <tr>
                <th>Gamis</th>
                <th>Upah</th>
                <th>Status</th>
                <th>Bulan</th>
                <th>Tahun</th>
                @role('lurah')
                    <th>Cabang</th>
                @endrole
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
                <td colspan="6" style="font-weight: 500">Cabang: {{ $nama_cabang ? $nama_cabang->nama : 'Semua Cabang' }}</td>
            </tr>
            <tr>
                <td colspan="6" style="font-weight: 500">Status (Gamis): {{ $transaksi->where('status', 'Gamis')->count() }} orang</td>
            </tr>
            <tr>
                <td colspan="6" style="font-weight: 500">Status (Lulus): {{ $transaksi->where('status', 'Lulus')->count() }}</td>
            </tr>
            <tr>
                <td colspan="6" style="font-weight: 500">Total Pendapatan Gamis: Rp{{ number_format($transaksi->sum('upah'), 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
@endsection
