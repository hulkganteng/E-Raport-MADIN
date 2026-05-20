<?php

namespace App\Support;

class RaportArabic
{
    private const DIGITS = [
        '0' => '٠',
        '1' => '١',
        '2' => '٢',
        '3' => '٣',
        '4' => '٤',
        '5' => '٥',
        '6' => '٦',
        '7' => '٧',
        '8' => '٨',
        '9' => '٩',
    ];

    private const PREDICATES = [
        'A' => 'أ',
        'B' => 'ب',
        'C' => 'ج',
        'D' => 'د',
        'E' => 'هـ',
    ];

    private const LABELS = [
        'exam_results' => 'الدرجات العقلية',
        'subject_name' => 'المواد الدراسية',
        'number' => 'رقماً',
        'letter' => 'كتابة',
        'general' => 'عام',
        'special' => 'خاص',
        'branch' => 'النوع',
        'total' => 'المجموع',
        'average' => 'المعدل',
        'ranking' => 'الدرجة',
        'personality' => 'أحوال الطالب',
        'attendance' => 'كشف الغياب',
        'akhlaq' => 'أخلاق',
        'diligence' => 'حرفة',
        'discipline' => 'تأديب',
        'neatness' => 'النظافة',
        'sick' => 'بعذر',
        'permission' => 'بغير عذر',
        'absent' => 'بدون بيان',
        'attendance_total' => 'الجملة',
        'student_count_suffix' => 'طالب',
    ];

    private const SUBJECTS = [
        'AKHLAK' => 'الأخلاق',
        'HADITS' => 'الحديث',
        'HADIST' => 'الحديث',
        'FIQIH' => 'الفقه',
        'FEQIH' => 'الفقه',
        'FIQH' => 'الفقه',
        'TAUHID' => 'التوحيد',
        'ASWAJA' => 'أهل السنة والجماعة',
        'TARIKH' => 'التاريخ',
        'NAHWU' => 'النحو',
        'SHOROF' => 'الصرف',
        'SHARAF' => 'الصرف',
        'BAHASA ARAB' => 'اللغة العربية',
        'BAHASAARAB' => 'اللغة العربية',
        'FAROIDH' => 'الفرائض',
        'BMK' => 'تعليم قراءة الكتاب',
    ];

    public static function digits(string|int|float|null $value): string
    {
        return strtr((string) $value, self::DIGITS);
    }

    public static function predicate(?string $value): string
    {
        $value = strtoupper(trim((string) $value));

        return self::PREDICATES[$value] ?? $value;
    }

    public static function label(string $key): string
    {
        return self::LABELS[$key] ?? $key;
    }

    public static function subjectName(?string $name): string
    {
        $name = trim((string) $name);
        $normalized = self::normalizeSubjectKey($name);

        return self::SUBJECTS[$normalized] ?? $name;
    }

    public static function gradeDescriptionArabic(?string $value): string
    {
        $value = strtoupper(trim((string) $value));

        return match ($value) {
            'A' => 'ممتاز',
            'B' => 'جيد جدا',
            'C' => 'جيد',
            'D' => 'مقبول',
            'E' => 'ضعيف',
            default => $value,
        };
    }

    public static function spellIndonesian(int|float|string|null $value): string
    {
        $number = (int) round((float) $value);

        if ($number === 0) {
            return 'Nol';
        }

        return ucwords(trim(self::spellIndonesianRaw($number)));
    }

    public static function spellIndonesianArabicScript(int|float|string|null $value): string
    {
        $words = explode(' ', strtolower(self::spellIndonesian($value)));
        $mapped = array_map(fn ($word) => self::INDONESIAN_ARABIC_WORDS[$word] ?? $word, $words);

        return implode(' ', $mapped);
    }

    private const INDONESIAN_ARABIC_WORDS = [
        'nol' => 'نول',
        'satu' => 'ساتو',
        'dua' => 'دوا',
        'tiga' => 'تيغا',
        'empat' => 'امفت',
        'lima' => 'ليما',
        'enam' => 'انام',
        'tujuh' => 'توجوه',
        'delapan' => 'دلافان',
        'sembilan' => 'سمبيلان',
        'sepuluh' => 'سفولوه',
        'sebelas' => 'سبلاس',
        'belas' => 'بلاس',
        'puluh' => 'فولوه',
        'seratus' => 'سراتوس',
        'ratus' => 'راتوس',
        'seribu' => 'سريبو',
        'ribu' => 'ريبو',
        'juta' => 'جوتا',
    ];

    private static function normalizeSubjectKey(string $name): string
    {
        return preg_replace('/[^A-Z0-9]+/', '', strtoupper($name)) ?: strtoupper($name);
    }

    private static function spellIndonesianRaw(int $value): string
    {
        $words = [
            0 => 'nol',
            1 => 'satu',
            2 => 'dua',
            3 => 'tiga',
            4 => 'empat',
            5 => 'lima',
            6 => 'enam',
            7 => 'tujuh',
            8 => 'delapan',
            9 => 'sembilan',
            10 => 'sepuluh',
            11 => 'sebelas',
        ];

        if ($value < 12) {
            return $words[$value];
        }

        if ($value < 20) {
            return self::spellIndonesianRaw($value - 10) . ' belas';
        }

        if ($value < 100) {
            return self::spellIndonesianRaw(intdiv($value, 10)) . ' puluh' . ($value % 10 ? ' ' . self::spellIndonesianRaw($value % 10) : '');
        }

        if ($value < 200) {
            return 'seratus' . ($value % 100 ? ' ' . self::spellIndonesianRaw($value % 100) : '');
        }

        if ($value < 1000) {
            return self::spellIndonesianRaw(intdiv($value, 100)) . ' ratus' . ($value % 100 ? ' ' . self::spellIndonesianRaw($value % 100) : '');
        }

        if ($value < 2000) {
            return 'seribu' . ($value % 1000 ? ' ' . self::spellIndonesianRaw($value % 1000) : '');
        }

        if ($value < 1000000) {
            return self::spellIndonesianRaw(intdiv($value, 1000)) . ' ribu' . ($value % 1000 ? ' ' . self::spellIndonesianRaw($value % 1000) : '');
        }

        return (string) $value;
    }
}
