<?php

namespace App\Console\Commands;

use App\Classes\SiteChecker;
use App\Models\Site;
use Illuminate\Console\Command;

class CheckSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:sites {url?}';

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
        if ($this->argument('url')) {
            $sites = Site::where(
                'url',
                $this->argument('url')
            )->get();
        } else {
            $sites = Site::with([
                'downs',
                'downsWithTrashed',
                'reports',
            ]);
        }

        $sites->each(function ($site) {
            $this->info('Checking ' . $site->url);
            SiteChecker::checkSite($site);
        });
    }
}
