<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cetak Struk Transaksi</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }
    </style>
</head>

<body style='font-family:tahoma; font-size:8pt;' onload="javascript:window.print()">
{{-- <body style='font-family:tahoma; font-size:8pt;'> --}}
    <center>
        <div id="logo">
            <img src="{!! asset("img/logo-laundry-simokerto.png") !!}" alt="logo" style="width: 70px; border-radius: 999px">
        </div>
        <table style='width:550px; font-size:8pt; font-family:calibri; border-collapse: collapse;' border = '0'>
            <td width='70%' align='left' style='padding-right:80px; vertical-align:top'>
                <span style='font-size:12pt'><b>{{ $cabang->nama }}</b></span></br>
                Alamat: {{ $cabang->alamat }} </br>
                Layanan Prioritas: {{ $transaksi->layananPrioritas->nama }} </br>
            </td>
            <td style='vertical-align:top' width='30%' align='left'>
                <b><span style='font-size:12pt'>Transaksi Layanan</span></b></br>
                Tanggal: {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d F Y') }}</br>
            </td>
        </table>
        <table style='width:550px; font-size:8pt; font-family:calibri; border-collapse: collapse;' border = '0'>
            <td width='70%' align='left' style='padding-right:80px; vertical-align:top'>
                Pegawai: {{ $transaksi->pegawai->username }}</br>
                Nota: {{ $transaksi->nota_pelanggan }}</br>
            </td>
            <td style='vertical-align:top' width='30%' align='left'>
                Pelanggan: {{ $transaksi->pelanggan->nama }}</br>
                Alamat : {{ $transaksi->pelanggan->alamat ? $transaksi->pelanggan->alamat : '-' }}</br>
                Telepon : {{ $transaksi->pelanggan->telepon }}</br>
            </td>
        </table>
        <table cellspacing='0' style='width:550px; font-size:8pt; font-family:calibri;  border-collapse: collapse; margin-top: 7px;' border='1'>
            <thead>
                <tr align='center' style="font-weight: 800">
                    <td width='10%'>Jenis Pakaian</td>
                    <td width='20%'>Jenis Layanan</td>
                    <td width='13%'>Total Pakaian</td>
                    <td width='4%'>Total Bayar</td>
                </tr>
            </thead>

            @foreach ($detailTransaksi as $item)
                <tr>
                    <td>{{ $item->detailLayananTransaksi[0]->hargaJenisLayanan->jenisPakaian->nama }}</td>
                    <td>
                        @foreach ($item->detailLayananTransaksi as $layanan)
                            {{ $layanan->hargaJenisLayanan->jenisLayanan->nama }};
                        @endforeach
                    </td>
                    <td>{{ $item->total_pakaian }} {{ $item->detailLayananTransaksi[0]->hargaJenisLayanan->jenis_satuan }}</td>
                    <td style='text-align:right'>Rp{{ number_format($item->total_biaya_layanan+$item->total_biaya_prioritas, 2, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4" style="color: transparent">-</td>
            </tr>

            <tr align='center' style="font-weight: 800">
                <td></td>
                <td>Layanan Tambahan</td>
                <td colspan="2"></td>
            </tr>

            @foreach ($layananTambahanTransaksi as $item)
                <tr>
                    <td></td>
                    <td>{{ $item->layananTambahan->nama }}</td>
                    <td></td>
                    <td style='text-align:right'>Rp{{ number_format($item->layananTambahan->harga, 2, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan='3'>
                    <div style='text-align:right'>Total Yang Harus Di Bayar Adalah : </div>
                </td>
                <td style='text-align:right'>Rp{{ number_format($transaksi->total_bayar_akhir, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div style='text-align:right'>Bayar : </div>
                </td>
                <td style='text-align:right'>Rp{{ number_format($transaksi->bayar, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div style='text-align:right'>Kembalian : </div>
                </td>
                <td style='text-align:right'>Rp{{ number_format($transaksi->kembalian, 2, ',', '.') }}</td>
            </tr>
        </table>

        <table style='width:650px; font-size:7pt;' cellspacing='5'>
            <tr>
                <td align='center'>Diterima Oleh,</br></br></br><u>({{ $transaksi->pelanggan->nama }})</u></td>
                <td style='border:0px solid black; padding:5px; text-align:left; width:30%'></td>
                <td align='center'>TTD,</br></br></br><u>(...........)</u></td>
            </tr>
        </table>
    </center>
</body>

</html>
