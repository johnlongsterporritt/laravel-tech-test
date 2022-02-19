<?php

namespace App\Console\Commands;

use App\Models\Authority;
use App\Models\Establishment;
use App\Models\Region;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class IngestEstablishments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingest:establishments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest establishments from FHRS';

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

        $authorities = Authority::select(['authority_id','region_id'])->get();

        foreach ($authorities as $authority) {
            $client = new Client([
                'base_uri' => 'https://api.ratings.food.gov.uk/Establishments?localAuthorityId='.$authority->authority_id.'&pageSize=5000',
                'timeout'  => 60,
            ]);
            $response = $client->request('GET', '', [
                'headers' => [
                    'x-api-version' => 2
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $this->verboseMessageInfo("Successfully connected to API...");

                $data = json_decode($response->getBody()->getContents());

                foreach ($data->establishments as $establishment) {
                    $address = $establishment->AddressLine1.' '.$establishment->AddressLine2.' '.$establishment->PostCode;

                    Establishment::updateOrCreate(
                        ['fhrs_id' => $establishment->FHRSID],
                        [
                            'name' => $establishment->BusinessName,
                            'business_type' => $establishment->BusinessType,
                            'address' => $address,
                            'phone' => $establishment->Phone,
                            'rating' => $establishment->scores->Hygiene,
                            'region_id' => $authority->region_id,
                            'authority_id' => $authority->authority_id,
                            'fhrs_id' => $establishment->FHRSID,
                        ]
                    );

                    $this->verboseMessageInfo("Updated {$establishment->BusinessName} Establishment...");
                }
            }
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
