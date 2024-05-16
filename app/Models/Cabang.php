<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Cabang extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $table = 'cabang';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nama',
        'slug',
        'lokasi',
        'alamat',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nama')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
