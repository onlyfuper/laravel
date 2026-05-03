<?php

namespace Local\StarterKit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SpriteGenerateCommand extends Command
{
    protected $signature = 'sprite:generate
                            {--source= : SVG source directory (default: resources/svg)}
                            {--output= : Output file path (default: public/static/sprite/sprite.svg)}';

    protected $description = 'Generate an SVG sprite file from individual SVG icons.';

    public function handle(): int
    {
        $sourcePath = $this->option('source') ?? resource_path('svg');
        $outputPath = $this->option('output') ?? public_path('static/sprite/sprite.svg');

        if (! File::isDirectory($sourcePath)) {
            $this->components->error("SVG source directory not found: {$sourcePath}");
            return self::FAILURE;
        }

        $files = collect(File::files($sourcePath))
            ->filter(fn ($f) => strtolower($f->getExtension()) === 'svg')
            ->sortBy(fn ($f) => $f->getFilenameWithoutExtension());

        if ($files->isEmpty()) {
            $this->components->warn("No SVG files found in: {$sourcePath}");
            return self::SUCCESS;
        }

        $symbols = '';

        foreach ($files as $file) {
            $name    = $file->getFilenameWithoutExtension();
            $content = File::get($file->getPathname());

            // Remove XML declaration and DOCTYPE
            $content = preg_replace('/<\?xml[^>]*\?>/i', '', $content);
            $content = preg_replace('/<!DOCTYPE[^>]*>/i', '', $content);

            // Remove stroke and stroke-width from all elements
            $content = preg_replace('/\s+stroke="[^"]*"/i', '', $content);
            $content = preg_replace('/\s+stroke-width="[^"]*"/i', '', $content);

            // Convert <svg ...> → <symbol id="name" ...>
            $content = preg_replace_callback(
                '/<svg([^>]*)>/i',
                function ($matches) use ($name) {
                    $attrs = $matches[1];
                    $attrs = preg_replace('/\s+xmlns(:\w+)?="[^"]*"/i', '', $attrs);
                    $attrs = preg_replace('/\s+(width|height)="[^"]*"/i', '', $attrs);
                    $attrs = preg_replace('/\s+stroke="[^"]*"/i', '', $attrs);
                    $attrs = preg_replace('/\s+stroke-width="[^"]*"/i', '', $attrs);
                    return '<symbol id="' . $name . '"' . $attrs . '>';
                },
                $content,
            );

            // Convert </svg> → </symbol>
            $content = str_ireplace('</svg>', '</symbol>', $content);

            $symbols .= trim($content) . PHP_EOL;
        }

        $sprite = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" style="display:none">
        {$symbols}</svg>
        SVG;

        File::ensureDirectoryExists(dirname($outputPath));
        File::put($outputPath, $sprite);

        $count = $files->count();
        $this->components->info("Sprite generated: {$outputPath} ({$count} icons)");

        return self::SUCCESS;
    }
}
