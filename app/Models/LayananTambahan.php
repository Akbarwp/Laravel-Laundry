<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LayananTambahan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'layanan_tambahan';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nama',
        'harga',
        'cabang_id',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
