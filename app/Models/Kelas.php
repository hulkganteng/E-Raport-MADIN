<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $guarded = [];

    public function old_wali_kelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function wali_kelas()
    {
        return $this->hasMany(WaliKelas::class);
    }

    public function current_wali_kelas()
    {
        return $this->hasOne(WaliKelas::class)->whereHas('periode', function ($query) {
            $query->where('is_active', true);
        });
    }

    public function santri()
    {
        return $this->hasMany(Santri::class);
    }
    
    public function kelas_mapel()
    {
        return $this->hasMany(KelasMapel::class);
    }

    public function riwayat_kelas()
    {
        return $this->hasMany(RiwayatKelas::class);
    }
}
