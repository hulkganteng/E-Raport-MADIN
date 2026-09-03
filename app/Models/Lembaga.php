<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    protected $table = 'lembaga';
    protected $fillable = [
        'nama_lembaga',
        'jenjang',
        'nama_sekolah',
        'npsn',
        'nsm',
        'nss',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'website',
        'nama_kepala',
        'nip_kepala',
        'logo',
        'kkm_default',
        'grade_min_a',
        'grade_min_b',
        'grade_min_c',
    ];

    /**
     * Main (single) settings record.
     */
    public static function main(): ?self
    {
        return cache()->rememberForever('lembaga.settings', function () {
            $settings = self::query()->orderBy('id')->first();
            if ($settings === null) {
                $settings = self::create($defaults = [
                    'nama_lembaga' => 'Madrasah Diniyah',
                    'jenjang' => 'Madrasah Diniyah',
                    'kkm_default' => 75,
                    'grade_min_a' => 85,
                    'grade_min_b' => 75,
                    'grade_min_c' => 60,
                ]);
            }
            return $settings;
        });
    }

    public static function flushCache(): void
    {
        cache()->forget('lembaga.settings');
    }

    /**
     * Full address line built from parts.
     */
    public function fullAddress(): string
    {
        $parts = array_filter([
            $this->alamat,
            $this->desa,
            $this->kecamatan,
            $this->kabupaten,
            $this->provinsi,
            $this->kode_pos ? 'Kode Pos ' . $this->kode_pos : null,
        ], fn ($v) => $v !== null && trim((string) $v) !== '');

        return implode(', ', $parts);
    }

    /**
     * Display name: prefers nama_lembaga, falls back to app name.
     */
    public function displayName(): string
    {
        return trim((string) $this->nama_lembaga) !== ''
            ? $this->nama_lembaga
            : (string) config('app.name', 'E-Raport');
    }

    /**
     * API of settings as a simple array for quick field access.
     */
    public function allAttributes(): array
    {
        return $this->getAttributes();
    }
}
