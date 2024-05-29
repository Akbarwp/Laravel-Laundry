<?php

namespace App\Enums;

enum StatusTransaksi: string
{
    case BARU = "Baru";
    case PROSES = "Proses";
    case SIAP_DIAMBIL = "Siap Diambil";
    case PENGANTARAN = "Pengantaran";
    case SELESAI = "Selesai";
    case BATAL = "Batal";
}
