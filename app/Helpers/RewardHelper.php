<?php

namespace App\Helpers;

class RewardHelper
{
    public static function getNameByPhysicalType(string $type): string
    {
        return match ($type) {
            'giai1phuquy' => 'Giải Nhất Phú Quý',
            'giai2phuquy' => 'Giải Nhì Phú Quý',
            'giai3phuquy' => 'Giải Ba Phú Quý',
            'giai1thinhvuong' => 'Giải Nhất Thịnh Vượng',
            'giai2thinhvuong' => 'Giải Nhì Thịnh Vượng',
            'giai3thinhvuong' => 'Giải Ba Thịnh Vượng',
            'giai1tailoc' => 'Giải Nhất Tài Lộc',
            'giai2tailoc' => 'Giải Nhì Tài Lộc',
            'giai3tailoc' => 'Giải Ba Tài Lộc',
            default => ''
        };
    }
}
