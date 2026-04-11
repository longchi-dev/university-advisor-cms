<?php

namespace App\Console\Commands;

use App\Jobs\Import\ImportLeaderBoardDataExcelJob;
use Illuminate\Console\Command;

class TestImportLeaderBoardData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-leader-board-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Import';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = 'imports/data_import_example.xlsx';

        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            $this->error("File not found: {$fullPath}");
            return;
        }

        $this->info("Dispatching import job...");

        dispatch(new ImportLeaderBoardDataExcelJob($filePath));

        $this->info("Job dispatched successfully!");
    }
}
