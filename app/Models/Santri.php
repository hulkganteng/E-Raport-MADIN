<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table = 'santri';
    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function biodata()
    {
        return $this->hasOne(SantriBiodata::class);
    }
    
    public function nilai_mapel()
    {
        return $this->hasMany(NilaiMapel::class);
    }

    public function riwayat_kelas()
    {
        return $this->hasMany(RiwayatKelas::class);
    }
}
