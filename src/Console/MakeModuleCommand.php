<?php

namespace Local\StarterKit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name : Module name (e.g. Product)}
                                        {--force : Overwrite existing files}';

    protected $description = 'Generate a full CRUD module: Model, Migration, Livewire Index+Form, Views, Routes';

    public function handle(): void
    {
        $name  = Str::studly($this->argument('name'));
        $names = Str::plural(Str::snake($name, '_'));
        $slug  = Str::slug(Str::snake($name, '-'));
        $slugs = Str::plural($slug);

        $vars = [
            '{{Name}}'  => $name,
            '{{name}}'  => Str::camel($name),
            '{{names}}' => Str::camel(Str::plural($name)),
            '{{Names}}' => Str::title(str_replace('_', ' ', $names)),
            '{{slug}}'  => $slug,
            '{{slugs}}' => $slugs,
            '{{table}}' => $names,
        ];

        $this->info("Generating module: <comment>{$name}</comment>");

        $this->generateModel($name, $vars);
        $this->generateMigration($names, $vars);
        $this->generateLivewireIndex($name, $vars);
        $this->generateLivewireForm($name, $vars);
        $this->generateViews($slug, $vars);
        $this->appendRoutes($name, $slug, $vars);

        $this->newLine();
        $this->components->info("Module <comment>{$name}</comment> created successfully.");
        $this->line("  → Run <comment>php artisan migrate</comment> to create the table.");
        $this->line("  → Routes added under <comment>/admin/{$slugs}</comment>");
    }

    private function generateModel(string $name, array $vars): void
    {
        $this->components->task("Model: app/Models/{$name}.php", function () use ($name, $vars) {
            $path = app_path("Models/{$name}.php");
            if (File::exists($path) && ! $this->option('force')) {
                $this->warn("  Skipped (already exists). Use --force to overwrite.");
                return;
            }
            File::ensureDirectoryExists(app_path('Models'));
            File::put($path, $this->stub('Model.php.stub', $vars));
        });
    }

    private function generateMigration(string $names, array $vars): void
    {
        $this->components->task("Migration: create_{$names}_table", function () use ($names, $vars) {
            $existing = glob(database_path("migrations/*_create_{$names}_table.php"));
            if ($existing && ! $this->option('force')) {
                $this->warn("  Skipped (already exists).");
                return;
            }
            $filename = now()->format('Y_m_d_His') . "_create_{$names}_table.php";
            File::ensureDirectoryExists(database_path('migrations'));
            File::put(database_path("migrations/{$filename}"), $this->stub('migration.php.stub', $vars));
        });
    }

    private function generateLivewireIndex(string $name, array $vars): void
    {
        $this->components->task("Livewire: app/Livewire/Admin/{$name}/Index.php", function () use ($name, $vars) {
            $path = app_path("Livewire/Admin/{$name}/Index.php");
            if (File::exists($path) && ! $this->option('force')) {
                $this->warn("  Skipped.");
                return;
            }
            File::ensureDirectoryExists(app_path("Livewire/Admin/{$name}"));
            File::put($path, $this->stub('Livewire/Index.php.stub', $vars));
        });
    }

    private function generateLivewireForm(string $name, array $vars): void
    {
        $this->components->task("Livewire: app/Livewire/Admin/{$name}/Form.php", function () use ($name, $vars) {
            $path = app_path("Livewire/Admin/{$name}/Form.php");
            if (File::exists($path) && ! $this->option('force')) {
                $this->warn("  Skipped.");
                return;
            }
            File::ensureDirectoryExists(app_path("Livewire/Admin/{$name}"));
            File::put($path, $this->stub('Livewire/Form.php.stub', $vars));
        });
    }

    private function generateViews(string $slug, array $vars): void
    {
        foreach (['index', 'form'] as $view) {
            $this->components->task("View: resources/views/admin/{$slug}/{$view}.blade.php", function () use ($slug, $view, $vars) {
                $path = resource_path("views/admin/{$slug}/{$view}.blade.php");
                if (File::exists($path) && ! $this->option('force')) {
                    $this->warn("  Skipped.");
                    return;
                }
                File::ensureDirectoryExists(resource_path("views/admin/{$slug}"));
                File::put($path, $this->stub("views/{$view}.blade.stub", $vars));
            });
        }
    }

    private function appendRoutes(string $name, string $slug, array $vars): void
    {
        $slugs = Str::plural($slug);
        $this->components->task("Routes: /admin/{$slugs}", function () use ($name, $slug, $slugs) {
            $routeFile = base_path('routes/web.php');
            $marker    = "admin.{$slug}.index";

            if (str_contains(File::get($routeFile), $marker)) {
                $this->warn("  Skipped (routes already exist).");
                return;
            }

            $routes = <<<PHP


// {$name} module
Route::prefix('admin/{$slugs}')->middleware('auth')->name('admin.{$slug}.')->group(function () {
    Route::get('/', App\Livewire\Admin\\{$name}\Index::class)->name('index');
    Route::get('/create', App\Livewire\Admin\\{$name}\Form::class)->name('create');
    Route::get('/{{$slug}}/edit', App\Livewire\Admin\\{$name}\Form::class)->name('edit');
});
PHP;
            File::append($routeFile, $routes);
        });
    }

    private function stub(string $filename, array $vars): string
    {
        $stubPath = __DIR__ . '/../../stubs/module/' . $filename;
        $content  = File::get($stubPath);

        return str_replace(array_keys($vars), array_values($vars), $content);
    }
}
