<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Jobs\ProcessUniversityDataJob;
use App\Models\ImportJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ImportDataController extends Controller
{
    public function index(): View
    {
        return view('import_data.index');
    }


    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'extensions:json',
            ]
        ]);


        $file = $request->file('file');


        $json = file_get_contents(
            $file->getRealPath()
        );


        $data = json_decode($json, true);


        if (!is_array($data) || empty($data)) {

            return response()->json([
                'success' => false,
                'message' => 'File JSON không hợp lệ'
            ]);
        }


        $jobId = (string) Str::uuid();


        $path = "import/{$jobId}.json";


        Storage::disk('local')->put(
            $path,
            $json
        );


        ImportJob::query()->create([
            'job_id' => $jobId,
            'type' => 'university_data',
            'total' => count($data),
            'processed' => 0,
            'status' => JobStatus::PENDING,
            'file_path' => $path,
        ]);


        ProcessUniversityDataJob::dispatch(
            jobId: $jobId,
            total: count($data),
        );


        return response()->json([
            'success' => true,
            'job_id' => $jobId,
        ]);
    }


    public function importStatus(
        string $jobId
    ): JsonResponse {

        $job = ImportJob::query()
            ->where('job_id', $jobId)
            ->firstOrFail();


        return response()->json([
            'success' => true,
            'data' => [
                'job_id' => $job->job_id,
                'status' => $job->status->value,
                'total' => $job->total,
                'processed' => $job->processed,
                'file_path' => $job->file_path,
                'error' => $job->error,
                'started_at' => $job->started_at,
                'finished_at' => $job->finished_at,
            ],
        ]);
    }
}
