<?php

namespace App\Services\Export;

use App\Helpers\ImageHelper;
use App\Models\GamingSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GamingSessionExportService
{
    public function getGamingSessionDataExport(
        int $offset,
        int $limit,
        ?string $themeId = null,
        ?string $isSharedFb = null,
        ?string $isSharedIg = null,
        ?string $isSaved = null,
        ?string $fromDate = null,
        ?string $toDate = null,
    ): Collection {
        $query = GamingSession::query()
            ->with([
                'player:uuid,first_name',
                'uploadImage:uuid,player_id,path,terms_of_use',
                'outcomeImage',
                'theme:id,label',
            ])
            ->when($themeId, function ($q) use ($themeId) {
                $q->where('theme_id', $themeId);
            })
            ->when($isSharedFb !== null, function ($q) use ($isSharedFb) {
                $this->applyOutcomeImageFilter($q, $isSharedFb, 'share_fb_at');
            })
            ->when($isSharedIg !== null, function ($q) use ($isSharedIg) {
                $this->applyOutcomeImageFilter($q, $isSharedIg, 'share_ig_at');
            })
            ->when($isSaved !== null, function ($q) use ($isSaved) {
                $this->applyOutcomeImageFilter($q, $isSaved, 'save_at');
            })
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween(DB::raw('DATE(finished_at)'), [$fromDate, $toDate]);
            })
            ->orderBy('finished_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function (GamingSession $gamingSession) {
                $upload = $gamingSession->uploadImage;
                $outcome = $gamingSession->outcomeImage;
                $player = $gamingSession->player;
                $theme  = $gamingSession->theme;

                return [
                    'player_first_name' => $player?->first_name ?? null,
                    'full_url' => $gamingSession->full_url,
                    'theme_label' => $theme?->label ?? null,
                    'upload' => ImageHelper::getImageUrl($upload->path),
                    'image_has_frame' => $outcome?->image_has_frame ? ImageHelper::getImageUrl($outcome->image_has_frame) : null,
                    'started_at' => $gamingSession->created_at?->format('d-m-Y H:i:s') ?? null,
                    'finished_at' => $gamingSession->finished_at->format('d-m-Y H:i:s') ?? null,
                    'share_fb_at' => $outcome?->share_fb_at?->format('d-m-Y H:i:s') ?? null,
                    'share_ig_at' => $outcome?->share_ig_at?->format('d-m-Y H:i:s') ?? null,
                    'save_at' => $outcome?->save_at?->format('d-m-Y H:i:s') ?? null,
                ];
            });

        return $query->values();
    }

    public function getGamingSessionTotalCount(
        ?string $themeId = null,
        ?string $isSharedFb = null,
        ?string $isSharedIg = null,
        ?string $isSaved = null,
        ?string $fromDate = null,
        ?string $toDate = null,
    ): int {
        return GamingSession::query()
            ->with([
                'player:uuid,first_name',
                'player.uploadImage:uuid,player_id',
                'outcomeImage: id,player_id,gaming_session_id',
                'theme:id,label',
            ])
            ->when($themeId, function ($q) use ($themeId) {
                $q->where('theme_id', $themeId);
            })
            ->when($isSharedFb !== null, function ($q) use ($isSharedFb) {
                $this->applyOutcomeImageFilter($q, $isSharedFb, 'share_fb_at');
            })
            ->when($isSharedIg !== null, function ($q) use ($isSharedIg) {
                $this->applyOutcomeImageFilter($q, $isSharedIg, 'share_ig_at');
            })
            ->when($isSaved !== null, function ($q) use ($isSaved) {
                $this->applyOutcomeImageFilter($q, $isSaved, 'save_at');
            })
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween(DB::raw('DATE(finished_at)'), [$fromDate, $toDate]);
            })
            ->count();
    }

    private function applyOutcomeImageFilter($queryBuilder, $value, $column): void
    {
        if ($value === null) {
            return;
        }

        $queryBuilder->whereHas('outcomeImage', function ($q) use ($value, $column) {
            $value == '1' ? $q->whereNotNull($column) : $q->whereNull($column);
        });
    }
}
