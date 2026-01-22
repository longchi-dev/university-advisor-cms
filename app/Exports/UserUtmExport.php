<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Facades\DB;

class UserUtmExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'Zalo ID',
            'Full URL',
            'Agree ToC',
            'Agree Privacy',
            'Agree ToC At',
            'Agree Privacy At',
            'Created At'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->name,
            "=\"{$row->phone}\"",
            "=\"{$row->zalo_id}\"",
            (string) $row->full_url,
            $row->agree_toc ? 'Yes' : 'No',
            $row->agree_privacy ? 'Yes' : 'No',
            (string) $row->agree_toc_at,
            (string) $row->agree_privacy_at,
            (string) $row->created_at
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => '@',
            'C' => '@',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('user_utm')
            ->join('users', 'users.id', '=', 'user_utm.user_id')
            ->select('users.name', 'users.phone', 'users.zalo_id', 'user_utm.full_url', 'users.agree_toc', 'users.agree_privacy', 'users.agree_toc_at', 'users.agree_privacy_at', 'user_utm.created_at')
            ->orderBy('user_utm.created_at', 'DESC')
            ->get();
    }
}
