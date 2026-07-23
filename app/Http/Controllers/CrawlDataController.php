<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Jobs\Crawl\CrawlUniversityDataJob;
use App\Models\CrawlJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CrawlDataController extends Controller
{
    public function index(Request $request): View
    {
        $data = [];

        $data['fromYear'] = now()->year;
        $data['toYear'] = now()->year;

        return view('crawl_data.index', $data);
    }

    public function crawl(Request $request): JsonResponse
    {
        $request->validate([
            'from_year' => 'required|integer',
            'to_year'   => 'required|integer|gte:from_year',
        ]);

        $jobId = (string) Str::uuid();

        CrawlJob::query()->create([
            'job_id' => $jobId,
            'from_year' => $request->from_year,
            'to_year' => $request->to_year,
            'status' => JobStatus::PENDING,
        ]);

        CrawlUniversityDataJob::dispatch(
            $jobId,
            $request->from_year,
            $request->to_year,
        )->onQueue('crawl');

        return response()->json([
            'success' => true,
            'job_id' => $jobId,
        ]);
    }

    public function crawlStatus(string $jobId): JsonResponse
    {
        $job = CrawlJob::query()
            ->where('job_id', $jobId)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'job_id' => $job->job_id,
                'status' => $job->status->value,
                'from_year' => $job->from_year,
                'to_year' => $job->to_year,
                'total_records' => $job->total_records,
                'output_file' => $job->output_file,
                'log' => $job->log,
                'started_at' => $job->started_at,
                'finished_at' => $job->finished_at,
            ],
        ]);
    }
}
