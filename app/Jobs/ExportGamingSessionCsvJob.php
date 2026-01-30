<?php

namespace App\Jobs;

use App\Models\ExportJob;
use App\Services\Export\GamingSessionExportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExportGamingSessionCsvJob implements ShouldQueue
{
    use Queueable;

    protected string $jobId;
    protected int $total;
    protected int $offset;
    protected int $limit;
    protected ?string $fromDate;
    protected ?string $toDate;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $jobId,
        int $total,
        int $offset = 0,
        ?string $fromDate = null,
        ?string $toDate = null,
    ) {
        $this->jobId = $jobId;
        $this->total = $total;
        $this->offset = $offset;
        $this->limit = config('export.gaming_session_export_limit');
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->onQueue('export');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = app(GamingSessionExportService::class);
        try {
            $cacheKey = config('cache_key.export_key') . ":{$this->jobId}";
            $jobData = Cache::get($cacheKey);

            $job = ExportJob::query()
                ->where('job_id', $this->jobId)->firstOrFail();

            $path = $job->file_path;
            $fullPath = Storage::disk('public')->path($path);
            $handle = fopen($fullPath, 'a');

            $data = $service->getGamingSessionDataExport(
                offset: $this->offset,
                limit: $this->limit,
                fromDate: $this->fromDate,
                toDate: $this->toDate
            );

            foreach ($data as $row) {
                fputcsv($handle, [
//                    $row['player_name'],
//                    $row['terms_of_use'],
                    $row['upload'],
//                    $row['outcome_image_1'],
//                    $row['outcome_image_2'],
                    $row['image_has_frame'],
                    $row['started_at'],
                    $row['finished_at'],
                    $row['share_facebook_at'],
                ]);
            }

            fclose($handle);

            $processed = ($jobData['processed'] ?? 0) + count($data);
            $status = 'in_progress';

            if ($processed >= $this->total) {
                $status = 'completed';
            }

            $job->update([
                'status' => $status,
                'total' => $this->total,
                'processed' => $processed
            ]);

            // Update Cache
            Cache::put($cacheKey, [
                'job_id' => $this->jobId,
                'status' => $status,
                'total' => $this->total,
                'processed' => $processed,
                'file_path' => $path
            ], 3600);


            $nextOffset = $this->offset + $this->limit;
            if ($nextOffset < $this->total) {
                static::dispatch(
                    jobId: $this->jobId,
                    total: $this->total,
                    offset: $nextOffset,
                    fromDate: $this->fromDate,
                    toDate: $this->toDate
                );
            }
        } catch (\Exception $e) {
            $cacheKey = config('cache_key.export_key') . ":{$this->jobId}";

            ExportJob::query()
                ->where('job_id', $this->jobId)
                ->update(['status' => 'failed']);

            Cache::put($cacheKey, [
                'job_id' => $this->jobId,
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 3600);

            Log::error('Export Gaming Session failed', [
                'job_id' => $this->jobId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
