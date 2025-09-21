<?php

namespace App\Console\Commands;

use App\Jobs\SyncScreenFigmaData;
use Illuminate\Console\Command;

class SyncFigmaScreenData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'figma:sync-screens {--queue : Run the sync job in the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Figma node data for all screens with valid node IDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Figma screen data sync...');

        if ($this->option('queue')) {
            SyncScreenFigmaData::dispatch();
            $this->info('Sync job dispatched to queue.');
        } else {
            $job = new SyncScreenFigmaData();
            $job->handle();
            $this->info('Sync completed.');
        }
    }
}
