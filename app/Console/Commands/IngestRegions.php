<?php

namespace App\Console\Commands;

use App\Models\Region;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class IngestRegions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingest:regions {--fresh=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest regions from FHRS';

    private bool $verbose = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->verbose = $this->option('verbose');
        $fresh = $this->option('fresh') || false;

        if ($fresh) {
            Region::truncate();
            $this->verboseMessageInfo("Purged Region Table...");
        }

        $this->info("Starting Region Ingestion...");
        $client = new Client([
            'base_uri' => 'https://api.ratings.food.gov.uk/Regions/basic',
            'timeout'  => 2.0,
        ]);
        $response = $client->request('GET', '', [
            'headers' => [
                'x-api-version' => 2
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $this->verboseMessageInfo("Successfully connected to API...");

            $data = json_decode($response->getBody()->getContents());

            foreach ($data->regions as $region) {
                Region::updateOrCreate(
                    ['fhrs_id' => $region->id],
                    [
                        'name' => $region->name,
                        'name_key' => $region->nameKey,
                        'name_code' => $region->code,
                        'fhrs_id' => $region->id,
                    ]
                );

                $this->verboseMessageInfo("Updated {$region->name} Region...");
            }

        } else {
            $this->error("Could not reach API, quiting...");
        }
    }

    /**
     * Display message only on verbose
     *
     * @param $message
     */
    private function verboseMessageInfo($message) {
        if ($this->verbose) {
            $this->info($message);
        }
    }
}
