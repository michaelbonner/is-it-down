<?php

use Illuminate\Database\Seeder;
use App\Models\Site;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = collect(
            [
                'https://www.google.com/',
                'https://www.lineskis.com/',
                'https://www.brightonresort.com/',
                'https://httpstat.us/400',
                'https://httpstat.us/500',
                'https://httpstat.us/301',
                'https://expired.badssl.com/',
                'https://self-signed.badssl.com/',
            ]
        );

        $sites->each(function ($site) {
            factory(App\Models\Site::class)->create([
                'url' => $site,
            ]);
        });
    }
}
