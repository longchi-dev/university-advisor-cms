<?php

namespace App\Exports;

use App\Models\Reward;
use App\Helpers\RewardHelper;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RewardLogExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->data)->map(function ($row) {
            return [
                $row['admin_area'],
                $row['admin_name'],
                $row['username'],
                $row['reward_name'],
                date('d/m/Y H:i:s', strtotime($row['used_at'])),
                $row['phone_hashed'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Mã khu vực',
            'Tên',
            'Username',
            'Quà',
            'Ngày phát',
            'Số điện thoại(mã hoá)'
        ];
    }
}
