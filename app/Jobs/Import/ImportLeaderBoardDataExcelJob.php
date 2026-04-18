<?php

namespace App\Jobs\Import;

use App\Models\LeaderBoard;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportLeaderBoardDataExcelJob implements ShouldQueue
{
    use Queueable;

    protected string $jobId;
    protected string $filePath;
    protected int $week;
    protected int $offset;
    protected int $limit;

    public function __construct(
        string $jobId,
        string $filePath,
        int $week,
        int $offset = 0,
    ) {
        $this->jobId = $jobId;
        $this->filePath = $filePath;
        $this->week = $week;
        $this->offset = $offset;
        $this->limit = config('import.leader_board_import_limit');
        $this->onQueue('import');
    }

    /**
     * @throws IOException
     * @throws ReaderNotOpenedException|\Throwable
     */
    public function handle(): void
    {
        try {
            $path = storage_path('app/public/' . ltrim($this->filePath, '/'));

            if (!file_exists($path)) {
                throw new \Exception("File not found: {$path}");
            }

            DB::transaction(function () use ($path) {
                $reader = ReaderEntityFactory::createXLSXReader();
                $reader->open($path);

                $weekNumber = $this->week;

                $startDate = Carbon::parse(config('app.event_start_date'))->startOfDay();
                $weekStart = $startDate->copy()->addDays(($weekNumber - 1) * 7);

                $rows = [];
                $index = 0;

                LeaderBoard::query()->where('week_number', $weekNumber)->delete();

                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        $index++;

                        if ($index === 1) continue;

                        $cells = array_values($row->toArray());

                        $rows[] = [
                            'rank' => $cells[0] ?? null,
                            'name' => $cells[1] ?? null,
                            'phone' => $cells[2] ?? null,
                            'week_start_date' => $weekStart,
                            'week_number' => $weekNumber,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        if (count($rows) >= $this->limit) {
                            LeaderBoard::query()->insert($rows);
                            $rows = [];

                            Cache::put("import:{$this->jobId}", [
                                'status' => 'processing',
                                'processed' => $index
                            ]);
                        }
                    }
                }

                if (!empty($rows)) {
                    LeaderBoard::query()->insert($rows);
                }

                $reader->close();
            });

            Cache::put("import:{$this->jobId}", [
                'status' => 'completed'
            ], 3600);

        } catch (\Throwable $e) {
            Cache::put("import:{$this->jobId}", [
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 3600);

            Log::error('Import failed', [
                'job_id' => $this->jobId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
