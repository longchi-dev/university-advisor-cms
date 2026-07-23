<?php

namespace App\Jobs;

use App\Enums\JobStatus;
use App\Models\ImportJob;
use App\Services\UniversityDataService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessUniversityDataJob implements ShouldQueue
{
    use Queueable;

    private const QUEUE_NAME = 'import-university-data';
    private const STORAGE_DISK = 'local';

    public int $timeout = 360;
    public int $tries = 1;

    protected int $limit;

    public function __construct(
        protected string $jobId,
        protected int $total,
        protected int $offset = 0,
    ) {
        $this->onQueue(self::QUEUE_NAME);

        $this->limit = config(
            'import.university_data_import_limit',
            100
        );
    }

    /**
     * @throws \Throwable
     */
    public function handle(
        UniversityDataService $service
    ): void {

        try {
            $job = ImportJob::query()
                ->where('job_id', $this->jobId)
                ->firstOrFail();

            if ($job->status === JobStatus::PENDING) {
                $job->update([
                    'status' => JobStatus::IN_PROGRESS,
                    'started_at' => now(),
                ]);
            }

            if (! Storage::disk(self::STORAGE_DISK)->exists($job->file_path)
            ) {
                throw new \Exception(
                    "File not found: {$job->file_path}"
                );
            }

            $json = Storage::disk(self::STORAGE_DISK)->get($job->file_path);

            $data = json_decode($json, true);

            if (!is_array($data) || empty($data)) {
                throw new \Exception(
                    'Invalid JSON data'
                );
            }

            $chunk = array_slice(
                $data,
                $this->offset,
                $this->limit
            );

            if (empty($chunk)) {
                return;
            }

            $service->importData($chunk);
            $processed = $job->processed + count($chunk);
            $completed = $processed >= $this->total;

            $job->update([
                'processed' => $processed,
                'status' => $completed
                    ? JobStatus::COMPLETED
                    : JobStatus::IN_PROGRESS,

                'finished_at' => $completed
                    ? now()
                    : null,
            ]);

            if (!$completed) {
                self::dispatch(
                    jobId: $this->jobId,
                    total: $this->total,
                    offset: $this->offset + $this->limit,
                );
            }
        } catch (\Throwable $e) {
            ImportJob::query()
                ->where('job_id', $this->jobId)
                ->update([
                    'status' => JobStatus::FAILED,
                    'error' => $e->getMessage(),
                    'finished_at' => now(),
                ]);

            Log::error(
                'Import university data failed',
                [
                    'job_id' => $this->jobId,
                    'error' => $e->getMessage(),
                ]
            );

            throw $e;
        }
    }
}
