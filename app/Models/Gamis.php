<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gamis extends Model
{
    use HasFactory;

    protected $table = 'gamis';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'kartu_keluarga',
        'alamat',
        'rt',
        'rw',
    ];

    public function detailGamis()
    {
        return $this->hasMany(DetailGamis::class);
    }
}
