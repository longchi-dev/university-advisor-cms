<?php

namespace App\Jobs\Crawl;

use App\Enums\JobStatus;
use App\Models\CrawlJob;
use App\Services\CrawlUniversityDataService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CrawlUniversityDataJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 0;
    public int $tries = 1;
    public bool $failOnTimeout = true;

    public function __construct(
        protected string $jobId,
        protected int $fromYear,
        protected int $toYear,
    ) {
        $this->onQueue('crawl');
    }

    /**
     * @throws \Throwable
     */
    public function handle(): void
    {
        $job = CrawlJob::query()->where('job_id', $this->jobId)->firstOrFail();

        $job->update([
            'status' => JobStatus::IN_PROGRESS,
            'started_at' => now(),
        ]);

        try {
            $result = app(CrawlUniversityDataService::class)->crawl(
                $this->fromYear,
                $this->toYear,
                function (string $type, string $buffer) use ($job) {
                    $job->update([
                        'log' => ($job->log ?? '') . $buffer,
                    ]);

                }
            );

            $records = [];

            if (file_exists($result['file'])) {
                $records = json_decode(file_get_contents($result['file']), true) ?? [];
            }

            $job->update([
                'status' => JobStatus::COMPLETED,
                'finished_at' => now(),
                'total_records' => count($records),
                'output_file' => $result['file'],
                'log' => $result['output'],
            ]);
        } catch (\Throwable $e) {
            $job->update([
                'status' => JobStatus::FAILED,
                'finished_at' => now(),
                'log' => $e->getMessage(),
            ]);

            Log::error($e);

            throw $e;
        }
    }
}
