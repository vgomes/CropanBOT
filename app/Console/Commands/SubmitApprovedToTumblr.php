<?php

namespace Cropan\Console\Commands;

use Cropan\Picture;
use Illuminate\Console\Command;

class SubmitApprovedToTumblr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:tumblr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send images with enough votes to Tumblr';

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
        $pictures = Picture::where('published_at', null)
            ->where('score', '>', 0)
            ->where('yes', '>', 3)
            ->get();
        
        $pictures->each(function (Picture $picture) {
            
        });
    }
}
