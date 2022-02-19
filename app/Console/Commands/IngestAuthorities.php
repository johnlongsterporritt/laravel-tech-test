<?php

namespace App\Console\Commands;

use App\Models\Authority;
use App\Models\Region;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class IngestAuthorities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingest:authorities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest authorities from FHRS';

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

        $this->info("Starting Authorities Ingestion...");
        $client = new Client([
            'base_uri' => 'https://api.ratings.food.gov.uk/Authorities',
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

            foreach ($data->authorities as $authority) {
                $regionID = Region::where('name', $authority->RegionName)->first()->id;

                if ($regionID) {
                    Authority::updateOrCreate(
                        ['authority_id' => $authority->LocalAuthorityId],
                        [
                            'name' => $authority->Name,
                            'region_id' => $regionID,
                            'establishment_count' => $authority->EstablishmentCount,
                            'authority_id' => $authority->LocalAuthorityId,
                        ]
                    );

                    $this->verboseMessageInfo("Updated {$authority->Name} Authority...");
                }

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
