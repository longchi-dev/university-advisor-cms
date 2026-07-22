<?php

namespace App\Services;

class QuestionClusteringService
{
    private array $importantPhrases = [
        'điểm chuẩn',
        'công nghệ thông tin',
        'kỹ thuật phần mềm',
        'khoa học máy tính',
        'sư phạm',
        'công nghệ kỹ thuật vi mạch bán dẫn',
    ];

    public function cluster($questions)
    {
        $groups = [];

        foreach ($questions as $question) {

            // bỏ các câu hỏi follow-up không có ý nghĩa khi đứng độc lập
            if ($this->isFollowUp($question->message)) {
                continue;
            }

            $keywords = $this->extractKeywords($question->message);

            $matched = false;

            foreach ($groups as &$group) {

                $score = $this->similarity(
                    $keywords,
                    $group['keywords']
                );

                if ($score >= 0.3) {

                    $group['total']++;

                    $matched = true;

                    break;
                }
            }

            if (!$matched) {

                $groups[] = [
                    'keywords' => $keywords,
                    'question' => $question->message,
                    'total' => 1
                ];
            }
        }

        return collect($groups)
            ->sortByDesc('total')
            ->values();
    }


    /**
     * Trích xuất keyword phục vụ so sánh
     */
    private function extractKeywords(string $text): array
    {
        $text = mb_strtolower($text);


        // bỏ dấu câu
        $text = preg_replace(
            '/[^\p{L}\p{N}\s]/u',
            ' ',
            $text
        );


        $keywords = [];


        // ưu tiên lấy các cụm quan trọng
        foreach ($this->importantPhrases as $phrase) {

            if (str_contains($text, $phrase)) {

                $keywords[] = $phrase;

                $text = str_replace(
                    $phrase,
                    '',
                    $text
                );
            }
        }


        $words = explode(' ', $text);


        // các từ không mang nhiều ý nghĩa
        $stopWords = [
            'tôi',
            'mình',
            'cần',
            'muốn',
            'hỏi',
            'cho',
            'về',
            'là',
            'bao',
            'nhiêu',
            'có',
            'không',
            'của',
            'với',
            'nhỉ',
            'ạ',
            'này',
            'đó',
            'vậy',
            'thì',
        ];


        $keywords = array_merge(
            $keywords,
            collect($words)
                ->filter()
                ->reject(
                    fn ($word) =>
                    in_array($word, $stopWords)
                )
                ->values()
                ->toArray()
        );


        return array_values(
            array_unique($keywords)
        );
    }


    /**
     * Tính độ tương đồng giữa 2 tập keyword
     * Jaccard Similarity
     */
    private function similarity(array $a, array $b): float
    {
        $intersection = count(
            array_intersect($a, $b)
        );


        $union = count(
            array_unique(
                array_merge($a, $b)
            )
        );


        return $union > 0
            ? $intersection / $union
            : 0;
    }


    /**
     * Loại bỏ câu hỏi phụ thuộc context hội thoại
     */
    private function isFollowUp(string $text): bool
    {
        $text = mb_strtolower($text);


        $patterns = [
            'cái ngành đó',
            'ngành đó',
            'trường đó',
            'cái đó',
            'như vậy',
            'vậy ta',
        ];


        foreach ($patterns as $pattern) {

            if (str_contains($text, $pattern)) {
                return true;
            }
        }


        return false;
    }
}
