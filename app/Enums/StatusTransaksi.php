<?php

namespace App\Enums;

enum StatusTransaksi: string
{
    case BARU = "Baru";
    case PROSES = "Proses";
    case SIAP_DIAMBIL = "Siap Ambil";
    case PENGANTARAN = "Antar";
    case SELESAI = "Selesai";
    case BATAL = "Batal";
    // case Lunas = "Lunas";
    // case DP = "DP";
}
