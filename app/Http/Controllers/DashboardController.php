<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Exports\UserUtmExport;
use App\Http\Resources\ExportJobResource;
use App\Jobs\ExportUserUtmChunkJob;
use App\Models\ExportJob;
use App\Models\GamingSession;
use App\Models\OutcomeImage;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $data = [];

        $data['totalUsers'] = User::query()->whereNot('email', 'admin@gmail.com')->count();

        return view('dashboard', $data);
    }

    public function status($jobId): JsonResponse
    {
        $status = Cache::get(config('cache_key.export_key') . ":{$jobId}");
        if (!$status) {
            $job = ExportJob::query()->where('job_id', $jobId)->first();

            if (!$job) {
                return response()->json(['error' => 'Job not found'], 404);
            }

            $job->file_url = url('/storage/' . $job->file_path);

            return response()->json(new ExportJobResource($job));
        }

        if ($status['status'] === 'completed') {
            $status['file_url'] = url('/storage/' . $status['file_path']);
        } else {
            $status['file_url'] = null;
        }

        return response()->json($status);
    }
}
