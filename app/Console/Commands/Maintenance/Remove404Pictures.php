<?php

namespace Cropan\Console\Commands\Maintenance;

use Cropan\Picture;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;

class Remove404Pictures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:404';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes images no longer available';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pictures = Picture::all();

        $pictures->each(function (Picture $picture) {
            try {
                $answer = $this->client->get($picture->url, ['http_errors' => false]);

                if ($answer->getStatusCode() === 404) {
                    \Log::info("Deleting image $picture->id: $picture->url");
                    $picture->delete();
                }
            } catch (ConnectException $exception) {
                \Log::error($exception);
            }
        });

        return true;
    }
}
