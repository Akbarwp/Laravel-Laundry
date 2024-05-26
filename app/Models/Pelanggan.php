<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'telepon',
        'alamat',
        'cabang_id',
    ];
}
