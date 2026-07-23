<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Jobs\GenerateEmbeddingJob;
use App\Models\EmbeddingJob;
use App\Models\Score;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class EmbeddingController extends Controller
{
    public function index(): View
    {
        return view('embedding.index');
    }


    public function generate(): JsonResponse
    {
        $jobId = (string) Str::uuid();


        EmbeddingJob::query()->create([
            'job_id' => $jobId,
            'status' => JobStatus::PENDING,
            'total' => Score::whereDoesntHave('embedding')->count(),
            'processed' => 0,
        ]);


        GenerateEmbeddingJob::dispatch(
            jobId: $jobId
        );


        return response()->json([
            'success' => true,
            'job_id' => $jobId,
        ]);
    }


    public function generateStatus(
        string $jobId
    ): JsonResponse {

        $job = EmbeddingJob::query()
            ->where('job_id', $jobId)
            ->firstOrFail();


        return response()->json([
            'success' => true,
            'data' => [
                'job_id' => $job->job_id,
                'status' => $job->status->value,
                'total' => $job->total,
                'processed' => $job->processed,
                'log' => $job->log,
                'started_at' => $job->started_at,
                'finished_at' => $job->finished_at,
            ],
        ]);
    }
}
