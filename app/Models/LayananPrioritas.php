<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananPrioritas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'layanan_prioritas';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nama',
        'deskripsi',
        'jenis_satuan',
        'harga',
        'prioritas',
    ];
}
