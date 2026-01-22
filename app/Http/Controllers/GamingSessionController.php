<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\IGamingSessionRepository;
use App\Contracts\Repositories\IOutcomeImageRepository;
use App\Contracts\Repositories\IUploadImageRepository;
use App\Helpers\ImageHelper;
use App\Queries\GamingSession\GamingSessionHandler;
use App\Queries\GamingSession\GamingSessionQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class GamingSessionController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->get('perPage', 10);
        $page = (int) $request->get('page', 1);

        $data = [];
        $fromDate = $request->get('from_date', date('d-m-Y'));
        $toDate = $request->get('to_date', date('d-m-Y'));
        $fromDateCarbon = Carbon::parse($fromDate);
        $toDateCarbon = Carbon::parse($toDate);

        $gamingSessionQuery = new GamingSessionQuery(
            page: $page,
            perPage: $perPage,
            fromDate: $fromDateCarbon->toDateString(),
            toDate: $toDateCarbon->toDateString(),
        );

        $gamingSessions = app(GamingSessionHandler::class)->execute($gamingSessionQuery);

        $data['gamingSessions'] = $gamingSessions;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;
        return view('gaming_session.index', $data);
    }

    public function show(Request $request): View
    {
        $sessionId = $request->route('sessionId');
        $gamingSession = app(IGamingSessionRepository::class)->findById($sessionId);
        $upload = app(IUploadImageRepository::class)->findById($gamingSession->image_id);
        $outCome = app(IOutcomeImageRepository::class)->findBySessionId($sessionId);

        $images = $outCome ? [
            'image_1' => ImageHelper::getImageUrl($outCome->image_1),
            'image_2' => ImageHelper::getImageUrl($outCome->image_2)
        ]: [];

        return view('gaming_session.show', [
            'images' => $images,
            'uploadUrl' => ImageHelper::getImageUrl($upload->path),
            'hasFrameUrl' => $outCome?->image_has_frame ? ImageHelper::getImageUrl($outCome?->image_has_frame): null,
            'playerChooseImage' => $outCome?->player_choose_image?->value,
        ]);
    }
}
