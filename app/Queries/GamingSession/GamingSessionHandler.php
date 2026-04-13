<?php

namespace App\Queries\GamingSession;

use App\Contracts\Repositories\IOutcomeImageRepository;
use App\Contracts\Repositories\IPlayerRepository;
use App\Contracts\Repositories\IThemeRepository;
use App\Contracts\Repositories\IUploadImageRepository;
use App\Helpers\ImageHelper;
use App\Models\GamingSession;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GamingSessionHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(GamingSessionQuery $query): LengthAwarePaginator
    {
        $gamingSessionQuery = GamingSession::query()
            ->whereBetween(DB::raw('DATE(finished_at)'), [$query->fromDate, $query->toDate])
            ->orderBy('finished_at', 'desc');

        if ($query->themeId) {
            $gamingSessionQuery->where('theme_id', $query->themeId);
        }

        $filters = [
            'isSharedFb' => 'share_fb_at',
            'isSharedIg' => 'share_ig_at',
            'isSaved' => 'save_at',
        ];

        foreach ($filters as $key => $column) {
            $this->applyOutcomeImageFilter(
                $gamingSessionQuery,
                $query->{$key},
                $column
            );
        }

        $paginator = $gamingSessionQuery->paginate(
            $query->perPage, [
                'uuid',
                'player_id',
                'image_id',
                'theme_id',
                'ip_address',
                'finished_at',
                'created_at'
            ], 'page', $query->page
        );

        $paginator->getCollection()->transform(function (GamingSession $gamingSession) {
            $player = app(IPlayerRepository::class)->findById($gamingSession->player_id);
            $upload = app(IUploadImageRepository::class)->findById($gamingSession->image_id);
            $outcome = app(IOutcomeImageRepository::class)->findBySessionId($gamingSession->uuid);
            $theme = app(IThemeRepository::class)->findById($gamingSession->theme_id);

            return [
                'id' => $gamingSession->uuid,
                'ip_address' => $gamingSession->ip_address,
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

        return $paginator;
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
