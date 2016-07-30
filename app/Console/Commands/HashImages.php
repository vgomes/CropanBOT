<?php

namespace Cropan\Console\Commands;

use Cropan\Picture;
use Exception;
use Illuminate\Console\Command;

class HashImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the image hash for images lacking it';

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
     * @return mixed
     */
    public function handle()
    {
        $pictures = Picture::whereNull('hash')->get();
        ini_set('memory_limit', '-1');
        $pictures->each(function (Picture $picture) {
            try {
                $picture->hash = hashPicture($picture->url);
                $picture->save();

                var_dump("id: $picture->id | url: $picture->url | hash: $picture->hash");
            } catch (Exception $e) {
                var_dump("Falló: $picture->id");
            }
        });
    }
}
