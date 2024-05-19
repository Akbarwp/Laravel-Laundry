<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiLaundry extends Model
{
    use HasFactory;

    protected $table = 'pegawai_laundry';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nama',
        'foto',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'telepon',
        'alamat',
        'mulai_kerja',
        'selesai_kerja',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
