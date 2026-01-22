<?php

namespace App\Jobs;

use App\Models\ExportJob;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ExportUserUtmChunkJob implements ShouldQueue
{
    protected $jobId;
    protected $offset;
    protected $limit;

    /**
     * Create a new job instance.
     */
    public function __construct($jobId, $offset = 0, $limit = 1000)
    {
        $this->jobId = $jobId;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $cacheKey = config('cache_key.export_key') . ":{$this->jobId}";
            $jobData = Cache::get($cacheKey);

            $job = ExportJob::query()
                ->where('job_id', $this->jobId)->firstOrFail();

            $total = $jobData['total'] ?? $job->total;
            if ($total == 0) {
                $total = DB::table('user_utm')->count();
            }

            $path = $job->file_path;

            $rows = DB::table('user_utm')
                ->join('users', 'users.id', '=', 'user_utm.user_id')
                ->select(
                    'users.name',
                    'users.phone',
                    'users.zalo_id',
                    'user_utm.full_url',
                    'users.agree_toc',
                    'users.agree_privacy',
                    'users.agree_toc_at',
                    'users.agree_privacy_at',
                    'user_utm.created_at'
                )
                ->orderBy('user_utm.created_at', 'DESC')
                ->offset($this->offset)
                ->limit($this->limit)
                ->get();

            // Append CSV with proper escaping
            $fullPath = Storage::disk('public')->path($path);
            $handle = fopen($fullPath, 'a'); // mở file ở chế độ append

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->name ?? '',
                    $row->phone ?? '',
                    $row->zalo_id ?? '',
                    $row->full_url ?? '',
                    $row->agree_toc ? 'Yes' : 'No',
                    $row->agree_privacy ? 'Yes' : 'No',
                    $row->agree_toc_at ? Carbon::parse($row->agree_toc_at)->format('Y-m-d H:i:s') : '',
                    $row->agree_privacy_at ? Carbon::parse($row->agree_privacy_at)->format('Y-m-d H:i:s') : '',
                    $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($handle);

            // Update progress
            $processed = ($jobData['processed'] ?? 0) + count($rows);
            $status = $processed >= $total ? 'completed' : 'in_progress';

            // Update DB
            $job->update([
                'status' => $status,
                'total' => $total,
                'processed' => $processed
            ]);

            // Update Cache
            Cache::put($cacheKey, [
                'job_id' => $this->jobId,
                'status' => $status,
                'total' => $total,
                'processed' => $processed,
                'file_path' => $path
            ], 3600);

            if ($processed < $total) {
                dispatch(new ExportUserUtmChunkJob($this->jobId, $this->offset + $this->limit, $this->limit));
            }
        } catch (\Exception $e) {
            // Handle error
            $cacheKey = config('cache_key.export_key') . ":{$this->jobId}";

            ExportJob::query()
                ->where('job_id', $this->jobId)
                ->update(['status' => 'failed']);

            Cache::put($cacheKey, [
                'job_id' => $this->jobId,
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 3600);
            Log::error('ExportUserUtmChunkJob failed', [
                'job_id' => $this->jobId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
