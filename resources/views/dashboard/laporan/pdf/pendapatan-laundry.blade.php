@extends('dashboard.laporan.layouts.main')

@section('tanggal')
    <p style="padding-bottom: 20px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</span></p>
@endsection

@section('tabel')
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Layanan Prioritas</th>
                <th>Total Bayar</th>
                <th>Pendapatan Laundry</th>
                <th>Pelanggan</th>
                <th>Pegawai</th>
                <th>Gamis</th>
                <th>Cabang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $value => $item)
                {{-- <tr>
                    <td class="text-capitalize text-center">{{ $item->id }}</td>
                </tr> --}}
                <tr>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                        </p>
                    </td>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            {{ $item->layananPrioritas->nama }}
                        </p>
                    </td>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            Rp{{ number_format($item->total_bayar_akhir, 2, ',', '.') }}
                        </p>
                    </td>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            Rp{{ number_format($item->pendapatan_laundry, 2, ',', '.') }}
                        </p>
                    </td>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            {{ $item->pelanggan->nama }}
                        </p>
                    </td>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            @if ($item->pegawai->roles[0]->name == 'manajer_laundry')
                                {{ $item->pegawai->manajer[0]->nama }}
                            @elseif ($item->pegawai->roles[0]->name == 'pegawai_laundry')
                                {{ $item->pegawai->pegawai[0]->nama }}
                            @elseif ($item->pegawai->roles[0]->name == 'lurah')
                                {{ $item->pegawai->lurah[0]->nama }}
                            @endif
                        </p>
                    </td>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            {{ $item->gamis_id ? $item->gamis->nama : "-" }}
                        </p>
                    </td>
                    <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                        <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                            {{ $item->nama_cabang }}
                        </p>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="8" style="font-weight: 500">Cabang: {{ $nama_cabang ? $nama_cabang->nama : 'Semua Cabang' }}</td>
            </tr>
            <tr>
                <td colspan="8" style="font-weight: 500">Total Pemasukkan: Rp{{ number_format($transaksi->sum('total_bayar_akhir'), 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="8" style="font-weight: 500">Total Pendapatan Laundry: Rp{{ number_format($transaksi->sum('pendapatan_laundry'), 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
@endsection
