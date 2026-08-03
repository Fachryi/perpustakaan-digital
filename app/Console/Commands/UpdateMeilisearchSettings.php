<?php

namespace App\Console\Commands;

use Meilisearch\Client;
use Illuminate\Console\Command;

class UpdateMeilisearchSettings extends Command
{
    protected $signature = 'meilisearch:update-settings';
    protected $description = 'Update MeiliSearch settings';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        $index = $client->index('literatur'); 

        $index->updateRankingRules([
            'typo',
            'words',
            'proximity',
            'attribute',
            'exactness'
        ]);

        $this->info('MeiliSearch settings updated successfully.');
    }
}
