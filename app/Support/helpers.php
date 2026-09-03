<?php

use App\Models\Lembaga;

if (!function_exists('lembaga')) {
    /**
     * Get the active institution settings.
     *
     * @return \App\Models\Lembaga|null
     */
    function lembaga(): ?Lembaga
    {
        return Lembaga::main();
    }
}

if (!function_exists('lembaga_setting')) {
    /**
     * Get a single institution setting value.
     */
    function lembaga_setting(string $key, mixed $default = ''): mixed
    {
        $settings = Lembaga::main();
        return $settings ? ($settings->{$key} ?? $default) : $default;
    }
}

if (!function_exists('lembaga_logo_url')) {
    /**
     * Public URL of the institution logo, or the bundled default logo.
     */
    function lembaga_logo_url(): string
    {
        $logo = lembaga_setting('logo', '');
        if (trim((string) $logo) === '') {
            return asset('logo.jpg');
        }

        if (preg_match('#^https?://#', $logo)) {
            return $logo;
        }

        return asset('storage/' . ltrim((string) $logo, '/'));
    }
}

if (!function_exists('lembaga_logo_path')) {
    /**
     * Absolute filesystem path of the institution logo for PDF rendering.
     */
    function lembaga_logo_path(): string
    {
        $logo = lembaga_setting('logo', '');
        if (trim((string) $logo) !== '') {
            if (preg_match('#^https?://#', $logo)) {
                return (string) $logo;
            }
            $diskPath = storage_path('app/public/' . ltrim((string) $logo, '/'));
            if (file_exists($diskPath)) {
                return $diskPath;
            }
        }

        return public_path('logo.jpg');
    }
}

if (!function_exists('resolve_predikat')) {
    /**
     * Determine letter predicate (A/B/C/D) for a numeric score using
     * institution-configured grade boundaries.
     */
    function resolve_predikat(float|int|string $score): string
    {
        $value = (float) $score;

        $minA = (float) lembaga_setting('grade_min_a', 85);
        $minB = (float) lembaga_setting('grade_min_b', 75);
        $minC = (float) lembaga_setting('grade_min_c', 60);

        if ($value >= $minA) {
            return 'A';
        }
        if ($value >= $minB) {
            return 'B';
        }
        if ($value >= $minC) {
            return 'C';
        }

        return 'D';
    }
}
