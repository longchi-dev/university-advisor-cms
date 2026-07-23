<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class SettingService
{
    protected string $cmsEnvPath;
    protected string $backendEnvPath;


    public function __construct()
    {
        $this->cmsEnvPath = base_path('.env');

        $this->backendEnvPath = dirname(base_path()) . '/be/.env';
    }


    public function getCmsEnv(string $key): ?string
    {
        return $this->getEnv(
            $this->cmsEnvPath,
            $key
        );
    }


    public function getBackendEnv(string $key): ?string
    {
        return $this->getEnv(
            $this->backendEnvPath,
            $key
        );
    }


    protected function getEnv(
        string $envPath,
        string $key
    ): ?string {
        if (!file_exists($envPath)) {
            return null;
        }

        $content = file_get_contents($envPath);

        if (!preg_match(
            "/^{$key}=(.*)$/m",
            $content,
            $matches
        )) {
            return null;
        }

        return trim(
            $matches[1],
            " \t\n\r\0\x0B\"'"
        );
    }


    /**
     * @throws \Exception
     */
    public function updateCmsEnv(array $values): void
    {
        $this->updateEnv(
            $this->cmsEnvPath,
            $values
        );
    }


    /**
     * @throws \Exception
     */
    public function updateBackendEnv(array $values): void
    {
        $this->updateEnv(
            $this->backendEnvPath,
            $values
        );

        $this->reloadBackendConfig();
    }



    protected function updateEnv(
        string $envPath,
        array $values
    ): void {
        if (!file_exists($envPath)) {
            throw new \Exception(
                "Env file not found: {$envPath}"
            );
        }

        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $value = '"' . str_replace(
                    '"',
                    '\\"',
                    $value
                ) . '"';

            if (preg_match(
                "/^{$key}=.*/m",
                $content
            )) {
                $content = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $content
                );
            } else {
                $content .= PHP_EOL . "{$key}={$value}";
            }
        }

        file_put_contents(
            $envPath,
            $content
        );
    }

    protected function reloadBackendConfig(): void
    {
        Process::fromShellCommandline(
            'php artisan octane:reload',
            dirname($this->backendEnvPath)
        )->run();


        Process::fromShellCommandline(
            'php artisan config:clear',
            dirname($this->backendEnvPath)
        )->run();


        Process::fromShellCommandline(
            'php artisan queue:restart',
            dirname($this->backendEnvPath)
        )->run();
    }
}
