<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IngestInitial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingest:initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest all needed data';

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
        $this->call('ingest:regions');
        $this->call('ingest:authorities');
        $this->call('ingest:establishments');
    }
}
