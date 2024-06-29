<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananTambahanTransaksi extends Model
{
    use HasFactory;

    protected $table = 'layanan_tambahan_transaksi';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'layanan_tambahan_id',
        'transaksi_id',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function layananTambahan()
    {
        return $this->belongsTo(LayananTambahan::class);
    }
}
