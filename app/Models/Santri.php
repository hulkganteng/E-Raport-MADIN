<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table = 'santri';
    protected $fillable = [
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp_ortu',
        'pin_orangtua',
        'kelas_id',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
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
