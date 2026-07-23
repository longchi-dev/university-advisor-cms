<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class SettingService
{
    protected string $backendPath;

    public function __construct()
    {
        $this->backendPath = dirname(base_path()) . '/be';
    }

    public function getBackendEnv(string $key): ?string
    {
        $envPath = $this->backendPath . '/.env';

        if (!file_exists($envPath)) {
            return null;
        }

        $content = file_get_contents($envPath);

        if (!preg_match("/^{$key}=(.*)$/m", $content, $matches)) {
            return null;
        }

        return trim($matches[1], " \t\n\r\0\x0B\"'");
    }

    public function updateBackendEnv(array $values): void
    {
        $envPath = $this->backendPath . '/.env';

        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {

            $value = '"' . str_replace('"', '\\"', $value) . '"';

            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $content
                );
            } else {
                $content .= PHP_EOL . "{$key}={$value}";
            }
        }

        file_put_contents($envPath, $content);

        $this->reloadBackendConfig();
    }

    protected function reloadBackendConfig(): void
    {
        Process::fromShellCommandline('php artisan octane:reload', $this->backendPath)->run();
        Process::fromShellCommandline('php artisan config:clear', $this->backendPath)->run();
        Process::fromShellCommandline('php artisan queue:restart', $this->backendPath)->run();
    }
}
