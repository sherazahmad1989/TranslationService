<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;

class GenerateTranslations extends Command
{
    protected $signature = 'translations:generate
                            {count=100000 : Number of translations to generate}
                            {--C|chunk-size=1000 : Number of records to process at once}';

    protected $description = 'Generate test translation records for performance testing';

    public function handle()
    {
        $count = (int)$this->argument('count');
        $chunkSize = (int)$this->option('chunk-size');

        $this->info("Starting to generate {$count} translations in chunks of {$chunkSize}...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        // Reset unique generator between chunks
        $factory = Translation::factory();

        for ($i = 0; $i < $count; $i += $chunkSize) {
            $currentChunk = min($chunkSize, $count - $i);

            // Reset Faker's unique modifier for each chunk
            $this->resetFakerUniqueGenerator();

            $factory->count($currentChunk)->create();
            $bar->advance($currentChunk);
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully generated {$count} translations!");
    }

    protected function resetFakerUniqueGenerator()
    {
        // Clear Faker's unique generator state
        $this->laravel->make('Faker\Generator')->unique(true);
    }
}
