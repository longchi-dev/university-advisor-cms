<?php

namespace App\Jobs;

use App\AI\Services\AIService;
use App\Contracts\AIClients\ILlmClient;
use App\Enums\JobStatus;
use App\Models\EmbeddingJob;
use App\Models\Score;
use App\Models\UniversityEmbedding;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateEmbeddingJob implements ShouldQueue
{
    use Queueable;

    private const QUEUE_NAME = 'ai-build-embedding';

    public int $timeout = 360;
    public int $tries = 1;

    protected int $limit;


    public function __construct(
        protected string $jobId
    ) {
        $this->onQueue(self::QUEUE_NAME);

        $this->limit = config('ai.ai_job.limit', 100);
    }


    /**
     * Execute the job.
     *
     * @throws \Throwable
     */
    public function handle(
        ILlmClient $llmClient,
        AIService $aiService
    ): void {

        $embeddingJob = EmbeddingJob::query()
            ->where('job_id', $this->jobId)
            ->firstOrFail();


        if ($embeddingJob->status === JobStatus::PENDING) {

            $embeddingJob->update([
                'status' => JobStatus::IN_PROGRESS,
                'started_at' => now(),
            ]);
        }


        try {

            $scores = Score::query()
                ->with([
                    'school',
                    'major'
                ])
                ->whereDoesntHave('embedding')
                ->orderBy('id')
                ->limit($this->limit)
                ->get();


            if ($scores->isEmpty()) {

                $embeddingJob->update([
                    'status' => JobStatus::COMPLETED,
                    'finished_at' => now(),
                    'log' => 'Embedding completed',
                ]);

                return;
            }


            $processed = 0;


            foreach ($scores as $score) {

                $text = $aiService->buildScoreEmbeddingText($score);

                $embedding = $llmClient->embedding($text);


                UniversityEmbedding::query()
                    ->updateOrCreate(
                        [
                            'score_id' => $score->getKey(),
                        ],
                        [
                            'content' => $text,

                            'searchable_text' => implode(' ', array_filter([
                                $score->school?->name,
                                $score->major?->name,
                                $score->year,
                                $score->block,
                                $score->note,
                                $score->school?->address,
                                $score->school?->phone,
                                $score->school?->website,
                            ])),

                            'embedding' =>
                                '[' . implode(',', $embedding->embedding) . ']',

                            'model' => $embedding->model,
                        ]
                    );


                $processed++;
            }


            $embeddingJob->increment(
                'processed',
                $processed
            );


            $currentProcessed = $embeddingJob->fresh()->processed;


            $embeddingJob->update([
                'log' => "Generated {$currentProcessed}/{$embeddingJob->total}"
            ]);


            $hasRemaining = Score::query()
                ->whereDoesntHave('embedding')
                ->exists();


            if ($hasRemaining) {

                self::dispatch(
                    $this->jobId
                );

                return;
            }


            $embeddingJob->update([
                'status' => JobStatus::COMPLETED,
                'finished_at' => now(),
                'log' => 'Embedding completed',
            ]);


        } catch (\Throwable $e) {


            $embeddingJob->update([
                'status' => JobStatus::FAILED,
                'finished_at' => now(),
                'log' => $e->getMessage(),
            ]);


            Log::error(
                'Generate embedding failed',
                [
                    'job_id' => $this->jobId,
                    'error' => $e->getMessage(),
                ]
            );


            throw $e;
        }
    }
}
