<?php

namespace App\Enums;

enum JenisPembayaran: string
{
    case TUNAI = "Tunai";
    case TRANSFER = "Transfer";
}
