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
        ?string $fromDate = null,
        ?string $toDate = null,
    ): Collection {
        $query = GamingSession::query()
            ->with([
                'player:id,name',
                'player.uploadImage:uuid,image_id',
                'outcomeImage:id,player_id,gaming_session_id',
            ])
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween(DB::raw('DATE(finished_at)'), [$fromDate, $toDate]);
            })
            ->orderBy('finished_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function (GamingSession $gamingSession) {
                $chosenImageUrl = null;
                $outcome = $gamingSession->outcomeImage;
                if ($outcome?->player_choose_image) {
                    $field = $outcome->player_choose_image->value;

                    if (!empty($outcome->{$field})) {
                        $chosenImageUrl = ImageHelper::getImageUrl($outcome->{$field});
                    }
                }

                return [
                    'player_name' => $gamingSession->player?->name,
                    'terms_of_use' =>  $gamingSession->player?->uploadImage?->terms_of_use ? "Chấp nhận" : "Từ chối",
                    'upload' => ImageHelper::getImageUrl($gamingSession->player?->uploadImage?->path),
                    'outcome_chosen' => $chosenImageUrl,
                    'image_has_frame' => $outcome?->image_has_frame ? ImageHelper::getImageUrl($outcome->image_has_frame) : null,
                    'started_at' => $gamingSession->created_at?->format('d-m-Y H:i:s'),
                    'finished_at' => $gamingSession->finished_at?->format('d-m-Y H:i:s'),
                    'share_facebook_at' => $outcome?->share_facebook_at?->format('d-m-Y H:i:s'),
                ];
            });

        return $query->values();
    }

    public function getGamingSessionTotalCount(
        ?string $fromDate = null,
        ?string $toDate = null,
    ): int {
        return GamingSession::query()
            ->with([
                'player:id,name',
                'player.uploadImage:uuid,image_id',
                'outcomeImage:id,player_id,gaming_session_id',
            ])
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween(DB::raw('DATE(finished_at)'), [$fromDate, $toDate]);
            })
            ->count();
    }
}
