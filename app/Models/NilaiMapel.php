<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMapel extends Model
{
    protected $table = 'nilai_mapel';
    protected $guarded = [];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    // Alias snake_case untuk kompatibilitas kode lama
    public function kelas_mapel()
    {
        return $this->kelasMapel();
    }
    
    // Relasi dengan nama camelCase supaya eager load kelasMapel.* tidak error
    public function kelasMapel()
    {
        return $this->belongsTo(KelasMapel::class, 'kelas_mapel_id');
    }

    public function mapel()
    {
        return $this->hasOneThrough(Mapel::class, KelasMapel::class, 'id', 'id', 'kelas_mapel_id', 'mapel_id');
    }
}
