<?php

namespace App\Services\Reward;

use Carbon\Carbon;

class GiveRewardDto
{
    public function __construct(
        public int $rewardEntryId,
        public int $userId,
        public string $rewardName,
        public string $rewardDescription,
        public \DateTimeInterface|string $awardedAt,
        public bool $isUsed,
        public \DateTimeInterface|string|null $usedAt,
    ) {
        if (is_string($this->awardedAt)) {
            $this->awardedAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->awardedAt);
        }
        if (is_string($this->usedAt)) {
            $this->usedAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->usedAt);
        }
    }
}
