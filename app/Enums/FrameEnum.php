<?php

namespace App\Enums;

enum FrameEnum: string
{
    case XANH_UOC_MO = 'XANH_UOC_MO';
    case XANH_TUONG_LAI = 'XANH_TUONG_LAI';
    case LA_CHAN_XANH = 'LA_CHAN_XANH';
    case DU_PHONG_XANH = 'DU_PHONG_XANH';

    public function map(): string
    {
        return match ($this) {
            self::XANH_UOC_MO   => 'Xanh Ước Mơ',
            self::XANH_TUONG_LAI => 'Xanh Tương Lai',
            self::LA_CHAN_XANH  => 'Lá Chắn Xanh',
            self::DU_PHONG_XANH => 'Dự Phòng Xanh',
        };
    }
}
