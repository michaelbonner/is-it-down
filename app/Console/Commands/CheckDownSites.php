<?php

namespace App\Console\Commands;

use App\Models\Down;
use App\Models\Site;
use App\Classes\SiteChecker;
use Illuminate\Console\Command;

class CheckDownSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:downsites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check site status and SSL certificate validity';

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
        $downs = Down::all()->pluck('site_id')->unique();
        if ($downs) {
            $sites = Site::find($downs)->each(function ($site) {
                $this->info('Checking ' . $site->url);
                SiteChecker::checkSite($site);
            });
            
            $this->info('Finished checking down sites');
            return;
        }

        $this->info('No down sites');
    }
}
