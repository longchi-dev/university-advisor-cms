<?php

namespace App\Queries\GamingSession;

use App\Contracts\Repositories\IOutcomeImageRepository;
use App\Contracts\Repositories\IPlayerRepository;
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

        if ($query->isShared != null) {
            $gamingSessionQuery->whereHas('outcomeImage', function ($q) use ($query) {
                if ($query->isShared == '1') {
                    $q->whereNotNull('share_facebook_at');
                }

                if ($query->isShared == '0') {
                    $q->whereNull('share_facebook_at');
                }
            });
        }

        $paginator = $gamingSessionQuery->paginate(
            $query->perPage, [
                'uuid',
                'player_id',
                'ip_address',
                'image_id',
                'finished_at',
                'created_at'
            ], 'page', $query->page
        );

        $paginator->getCollection()->transform(function (GamingSession $gamingSession) {
            $player = app(IPlayerRepository::class)->findById($gamingSession->player_id);
            $upload = app(IUploadImageRepository::class)->findById($gamingSession->image_id);
            $outcome = app(IOutcomeImageRepository::class)->findBySessionId($gamingSession->uuid);

            $chosenImageUrl = null;
            if ($outcome && $outcome->player_choose_image) {
                $field = $outcome->player_choose_image->value;

                if (!empty($outcome->{$field})) {
                    $chosenImageUrl = ImageHelper::getImageUrl($outcome->{$field});
                }
            }

            return [
                'id' => $gamingSession->uuid,
                'player_name' => $player?->name ?? null,
                'ip_address' => $gamingSession->ip_address,
                'terms_of_use' =>  $upload?->terms_of_use ? "Chấp nhận" : "Từ chối",
                'upload' => ImageHelper::getImageUrl($upload->path),
                'outcome_chosen' => $chosenImageUrl,
                'image_has_frame' => $outcome?->image_has_frame ? ImageHelper::getImageUrl($outcome->image_has_frame) : null,
                'started_at' => $gamingSession->created_at?->format('d-m-Y H:i:s') ?? null,
                'finished_at' => $gamingSession->finished_at->format('d-m-Y H:i:s') ?? null,
                'share_facebook_at' => $outcome?->share_facebook_at?->format('d-m-Y H:i:s') ?? null,
                'is_shared' => !is_null($outcome?->share_facebook_at),
            ];
        });

        return $paginator;
    }
}
