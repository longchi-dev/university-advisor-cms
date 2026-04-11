<?php

namespace App\Http\Controllers;

use App\Jobs\Import\ImportLeaderBoardDataExcelJob;
use App\Models\LeaderBoard;
use App\Queries\LeaderBoard\LeaderBoardHandler;
use App\Queries\LeaderBoard\LeaderBoardQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LeaderBoardController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->get('perPage', 10);
        $page = (int) $request->get('page', 1);

        $data = [];
//        $fromDate = $request->get('from_date', date('d-m-Y'));
//        $toDate = $request->get('to_date', date('d-m-Y'));
//        $fromDateCarbon = Carbon::parse($fromDate);
//        $toDateCarbon = Carbon::parse($toDate);

        $weekNumber = $request->get('week_number');

        $leaderBoardQuery = new LeaderBoardQuery(
            page: $page,
            perPage: $perPage,
            weekNumber: $weekNumber,
//            fromDate: $fromDateCarbon->toDateString(),
//            toDate: $toDateCarbon->toDateString(),
        );

        $leaderBoards = app(LeaderBoardHandler::class)->execute($leaderBoardQuery);

        $data['leaderBoards'] = $leaderBoards;
        $data['maxWeek'] = LeaderBoard::query()->max('week_number') ?? 1;
//        $data['fromDate'] = $fromDate;
//        $data['toDate'] = $toDate;
        return view('leader_board.index', $data);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        $file = $request->file('file');

        $jobId = uniqid();

        $startDate = Carbon::parse(config('app.event_start_date'))->startOfDay();
        $today = now()->startOfDay();

        $weekNumber = intdiv($startDate->diffInDays($today), 7) + 1;
        $date = $today->format('Y-m-d');

        $fileName = "leaderboard-week-{$weekNumber}-{$date}-{$jobId}.xlsx";

        $path = $file->storeAs('imports', $fileName, 'public');


        Cache::put("import:{$jobId}", [
            'status' => 'processing',
            'file_name' => $fileName,
        ], 3600);

        dispatch(new ImportLeaderBoardDataExcelJob(
            jobId: $jobId,
            filePath: $path
        ));

        return response()->json([
            'job_id' => $jobId
        ]);
    }

    public function importStatus($jobId): JsonResponse
    {
        $status = Cache::get("import:{$jobId}");

        if (!$status) {
            return response()->json([
                'status' => 'not_found'
            ], 404);
        }

        return response()->json($status);
    }
}
