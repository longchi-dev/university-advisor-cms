<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\IGamingSessionRepository;
use App\Contracts\Repositories\IOutcomeImageRepository;
use App\Contracts\Repositories\IUploadImageRepository;
use App\Enums\JobStatus;
use App\Helpers\ImageHelper;
use App\Jobs\Export\ExportGamingSessionCsvJob;
use App\Models\ExportJob;
use App\Models\Theme;
use App\Queries\GamingSession\GamingSessionHandler;
use App\Queries\GamingSession\GamingSessionQuery;
use App\Services\Export\GamingSessionExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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

        $themeId = $request->get('theme_id');
        $hasOutcome = $request->get('has_outcome');
        $isSharedFb = $request->get('is_shared_fb');
        $isSharedIg = $request->get('is_shared_ig');
        $isSaved = $request->get('is_saved');

        $gamingSessionQuery = new GamingSessionQuery(
            page: $page,
            perPage: $perPage,
            themeId: $themeId,
            hasOutcome: $hasOutcome,
            isSharedFb: $isSharedFb,
            isSharedIg: $isSharedIg,
            isSaved: $isSaved,
            fromDate: $fromDateCarbon->toDateString(),
            toDate: $toDateCarbon->toDateString(),
        );

        $gamingSessions = app(GamingSessionHandler::class)->execute($gamingSessionQuery);

        $data['gamingSessions'] = $gamingSessions;
        $data['themes'] = Theme::query()->get();
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

    public function export(Request $request): JsonResponse
    {
        $themeId = $request->get('theme_id');
        $isSharedFb = $request->get('is_shared_fb');
        $isSharedIg = $request->get('is_shared_ig');
        $isSaved = $request->get('is_saved');

        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $fromDateFormat = Carbon::parse($fromDate)->toDateString();
        $toDateFormat = Carbon::parse($toDate)->toDateString();

        $jobId = uniqid();
        $from = Carbon::parse($fromDate)->format('d-m');
        $to   = Carbon::parse($toDate)->format('d-m');
        $now  = Carbon::now()->format('dmY');
        $job = ExportJob::query()->create([
            'job_id' => $jobId,
            'status' => JobStatus::PENDING,
            'total'  => 0,
            'processed' => 0,
            'file_path' => "exports/gaming_sessions_{$from}_{$to}_{$now}_{$jobId}.csv",
        ]);

        Cache::put(config('cache_key.export_key') . ":{$jobId}", [
            'job_id' => $jobId,
            'status' => $job->status,
            'total' => 0,
            'processed' => 0,
            'file_path' => $job->file_path
        ], 3600);

        $total = app(GamingSessionExportService::class)->getGamingSessionTotalCount(
            themeId: $themeId,
            isSharedFb: $isSharedFb,
            isSharedIg: $isSharedIg,
            isSaved: $isSaved,
            fromDate: $fromDateFormat,
            toDate: $toDateFormat,
        );

        Storage::disk('public')->makeDirectory('exports');
        $fullPath = Storage::disk('public')->path($job->file_path);
        $handle = fopen($fullPath, 'w');

        fputcsv($handle, [
            'Tên người chơi',
            'Full url',
            'Ảnh upload',
            'Ảnh outcome',
            'Thời gian bắt đầu',
            'Thời gian kết thúc',
            'Thời gian chia sẻ fb',
            'Thời gian chia sẻ ig',
            'Thời gian lưu',
        ]);

        fclose($handle);

        dispatch(new ExportGamingSessionCsvJob(
            jobId: $jobId,
            total: $total,
            fromDate: $fromDateFormat,
            toDate: $toDateFormat
        ));

        return response()->json([
            'job_id' => $jobId,
            'status' => JobStatus::PENDING,
            'file_path' => $job->file_path,
            'file_url' => url('/storage/' . $job->file_path)
        ]);
    }
}
