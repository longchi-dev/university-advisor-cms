<?php

namespace App\Console\Commands;

use App\Helpers\KeywordHelper;
use App\Models\KeywordLabel;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $selectedIds = [37, 24, 32, 36, 17];
        $keywords = KeywordLabel::query()
            ->join('keywords as k', 'k.id', 'keyword_id')
            ->select('keyword_labels.id', 'k.type as category')
            ->get()
            ->toArray();
        $a = KeywordHelper::getDominantCategory($selectedIds, $keywords);
    }
}
