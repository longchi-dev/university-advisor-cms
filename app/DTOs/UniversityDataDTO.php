<?php

namespace App\DTOs;

class UniversityDataDTO
{
    public function __construct(
        public int $year,
        public string $school,
        public string $major,
        public float $score,
        public ?string $level,
        public ?string $block,
        public ?string $note,
        public ?string $address,
        public ?string $phone,
        public ?string $website,
        public ?string $sourceUrl,
    ) { }

    public static function fromArray(array $data): self
    {
        return new self(
            year: $data['year'],
            school: $data['school'],
            major: $data['major'],
            score: (float) $data['score'],
            level: $data['level'] ?? null,
            block: $data['block'] ?? null,
            note: $data['note'] ?? null,
            address: $data['metadata']['Địa chỉ'] ?? null,
            phone: $data['metadata']['Điện thoại'] ?? null,
            website: $data['metadata']['Website'] ?? null,
            sourceUrl: $data['source_url'] ?? null,
        );
    }
}
