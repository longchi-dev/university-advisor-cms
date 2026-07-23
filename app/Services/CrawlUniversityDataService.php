<?php

namespace App\Services;

use Closure;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CrawlUniversityDataService
{
    public function crawl(
        int $fromYear,
        int $toYear,
        ?Closure $callback = null,
    ): array {

        $python = base_path('venv/bin/python');

        $process = new Process([
            $python,
            '-u',
            base_path('scripts/crawl.py'),
            $fromYear,
            $toYear,
        ]);

        $process->setWorkingDirectory(base_path());
        $process->setTimeout(null);

        $process->run(function (string $type, string $buffer) use ($callback) {

            if ($callback) {
                $callback($type, $buffer);
            }

        });

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return [
            'output' => $process->getOutput(),
            'file' => 'diem_chuan.json',
        ];
    }
}
