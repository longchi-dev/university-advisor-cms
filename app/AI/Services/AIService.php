<?php

namespace App\AI\Services;
use App\Models\Score;

class AIService
{
    public function __construct(
    ) {
    }

    public function buildScoreEmbeddingText(Score $score): string
    {
        $contactInfo = [];
        if ($score->school->address) {
            $contactInfo[] = "Địa chỉ: {$score->school->address}.";
        }
        if ($score->school->phone) {
            $contactInfo[] = "Điện thoại: {$score->school->phone}.";
        }
        if ($score->school->website) {
            $contactInfo[] = "Website: {$score->school->website}.";
        }

        $contactText = !empty($contactInfo)
            ? " Thông tin liên hệ của trường: " . implode(' ', $contactInfo)
            : "";

        return trim(sprintf(
            "Trường %s tuyển sinh ngành %s năm %s có điểm chuẩn là %s. "
            . "Khối xét tuyển: %s. Hệ đào tạo: %s. %s%s",
            $score->school->name,
            $score->major->name,
            $score->year,
            $score->score,
            $score->block ?? 'không rõ',
            $score->level ?? 'chính quy',
            $score->note ? "Ghi chú: {$score->note}." : "",
            $contactText
        ));
    }
}
