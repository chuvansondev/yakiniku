<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class ApplicationHealth extends Command
{
    protected $signature = 'app:health';

    protected $description = 'Check database, queue, cache, and storage readiness';

    public function handle(): int
    {
        $failed = false;

        $this->check('Database connection', function (): string {
            DB::select('SELECT 1');

            return DB::connection()->getName().' connection is available';
        }, $failed);

        $this->check('Application schema', function (): string {
            $required = ['bookings', 'booking_status_histories'];
            $missing = array_values(array_filter($required, fn (string $table): bool => ! Schema::hasTable($table)));
            if ($missing !== []) {
                throw new \RuntimeException('Missing tables: '.implode(', ', $missing));
            }

            return 'booking tables are present';
        }, $failed);

        $this->check('Queue storage', function (): string {
            $driver = config('queue.default');
            if ($driver === 'database') {
                $required = [config('queue.connections.database.table', 'jobs'), config('queue.failed.table', 'failed_jobs')];
                $missing = array_values(array_filter($required, fn (string $table): bool => ! Schema::hasTable($table)));
                if ($missing !== []) {
                    throw new \RuntimeException('Missing queue tables: '.implode(', ', $missing));
                }
            }

            return "{$driver} queue configured; worker process must be monitored separately";
        }, $failed);

        $this->check('Cache store', function (): string {
            $key = 'app-health:'.Str::uuid();
            $cache = Cache::store();
            $cache->put($key, 'ok', 10);

            try {
                if ($cache->get($key) !== 'ok') {
                    throw new \RuntimeException('Cache read-back failed');
                }
            } finally {
                $cache->forget($key);
            }

            return config('cache.default').' store can read and write';
        }, $failed);

        $this->check('Storage directory', function (): string {
            $path = storage_path();
            if (! is_dir($path) || ! is_writable($path)) {
                throw new \RuntimeException("{$path} is missing or not writable");
            }

            return 'storage directory is writable';
        }, $failed);

        if ($failed) {
            $this->newLine();
            $this->components->error('One or more readiness checks failed.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->components->info('Application readiness checks passed.');

        return self::SUCCESS;
    }

    private function check(string $label, callable $callback, bool &$failed): void
    {
        try {
            $message = $callback();
            $this->components->task($label, fn (): bool => true);
            $this->line("  <fg=gray>{$message}</>");
        } catch (Throwable $exception) {
            $failed = true;
            $this->components->task($label, fn (): bool => false);
            $this->line('  <fg=red>'.$exception->getMessage().'</>');
        }
    }
}
