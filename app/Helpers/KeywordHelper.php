<?php

namespace App\Helpers;

class KeywordHelper
{
    public static function getDominantCategory(array $selectedIds, array $keywords): ?string {
        // Đếm số lượng category
        $counts = [];
        foreach ($selectedIds as $sid) {
            foreach ($keywords as $k) {
                if ($k['id'] === $sid) {
                    $cat = $k['category'];
                    $counts[$cat] = ($counts[$cat] ?? 0) + 1;
                    break;
                }
            }
        }

        // Tìm category nhiều nhất
        $dominant = null;
        $max = 0;
        foreach ($counts as $cat => $c) {
            if ($c > $max) {
                $max = $c;
                $dominant = $cat;
            }
        }

        return $dominant;
    }
}
