<?php

namespace App\Helpers;

class RegionGroupHelper
{
    const ID_M_BAC = 1;
    const ID_M_TRUNG = 2;
    const ID_M_TAY = 3;
    const ID_TAY_NGUYEN = 4;
    const ID_HCM = 5;
    const ID_DNB = 6;
    public static function getNameById($id): string
    {
        return match ($id) {
            1 => 'Miền Bắc',
            2 => 'Miền Trung',
            3 => 'Miền Tây',
            4 => 'Tây Nguyên',
            5 => 'HCM',
            6 => 'Đông Nam Bộ',
            default => '',
        };
    }
}
