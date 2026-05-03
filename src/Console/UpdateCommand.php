<?php

namespace Local\StarterKit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class UpdateCommand extends Command
{
    protected $signature = 'starter-kit:update
                            {--force : Overwrite all files without asking}
                            {--skip-build : Skip npm build step}';

    protected $description = 'Update starter kit stubs (components, views, JS, CSS) to the latest version.';

    private array $updated = [];
    private array $skipped = [];

    public function handle(): void
    {
        $this->info('Updating Starter Kit...');

        $force = $this->option('force');

        $this->updateFile(
            __DIR__ . '/../../stubs/css/app.css',
            resource_path('css/app.css'),
            'CSS',
            $force,
        );

        $this->updateFile(
            __DIR__ . '/../../stubs/js/app.js',
            resource_path('js/app.js'),
            'JS',
            $force,
        );

        $this->updateDirectory(
            __DIR__ . '/../../stubs/components',
            resource_path('views/components'),
            'Components',
            $force,
        );

        $this->updateDirectory(
            __DIR__ . '/../../stubs/views/layouts',
            resource_path('views/layouts'),
            'Layouts',
            $force,
        );

        $this->updateDirectory(
            __DIR__ . '/../../stubs/views/auth',
            resource_path('views/auth'),
            'Auth views',
            $force,
        );

        $this->updateDirectory(
            __DIR__ . '/../../stubs/views/admin',
            resource_path('views/admin'),
            'Admin views',
            $force,
        );

        $this->updateDirectory(
            __DIR__ . '/../../stubs/config',
            base_path('config'),
            'Config',
            $force,
        );

        if ($this->updated) {
            $this->newLine();
            $this->components->twoColumnDetail('<fg=green>Updated</>', implode(', ', $this->updated));
        }

        if ($this->skipped) {
            $this->components->twoColumnDetail('<fg=yellow>Skipped</>', implode(', ', $this->skipped));
        }

        if (! $this->option('skip-build') && $this->updated) {
            $this->newLine();
            $this->components->task('Building frontend assets', function () {
                $process = Process::fromShellCommandline('npm run build', base_path());
                $process->setTimeout(null)->mustRun();
            });
        }

        $this->newLine();
        $this->info('Starter Kit updated successfully.');
    }

    private function updateFile(string $stub, string $destination, string $label, bool $force): void
    {
        if (! File::exists($stub)) {
            return;
        }

        if (File::exists($destination) && ! $force) {
            if (! $this->confirm("Overwrite <fg=yellow>{$destination}</>?", true)) {
                $this->skipped[] = $label;
                return;
            }
        }

        File::ensureDirectoryExists(dirname($destination));
        File::copy($stub, $destination);
        $this->updated[] = $label;
    }

    private function updateDirectory(string $stubDir, string $destinationDir, string $label, bool $force): void
    {
        if (! File::isDirectory($stubDir)) {
            return;
        }

        $files = File::allFiles($stubDir);
        $overwritten = 0;
        $skippedFiles = 0;

        foreach ($files as $file) {
            $relative    = $file->getRelativePathname();
            $destination = $destinationDir . DIRECTORY_SEPARATOR . $relative;

            if (File::exists($destination) && ! $force) {
                if (! $this->confirm("Overwrite <fg=yellow>{$relative}</> in {$label}?", true)) {
                    $skippedFiles++;
                    continue;
                }
            }

            File::ensureDirectoryExists(dirname($destination));
            File::copy($file->getPathname(), $destination);
            $overwritten++;
        }

        if ($overwritten > 0) {
            $this->updated[] = "{$label} ({$overwritten} files)";
        }

        if ($skippedFiles > 0) {
            $this->skipped[] = "{$label} ({$skippedFiles} files)";
        }
    }
}
