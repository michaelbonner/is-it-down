<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

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
                'https://bootpackdigital.com/',
                'https://michaelbonner.dev/',
                'https://www.google.com/',
                'https://lineskis.com/',
                'https://brightonresort.com/',
                'https://httpstat.us/400',
                'https://httpstat.us/500',
                'https://httpstat.us/301',
                'https://expired.badssl.com/',
                'https://self-signed.badssl.com/',
            ]
        );

        $sites->each(function ($site) {
            factory(Site::class)->create([
                'url' => $site,
            ]);
        });
    }
}
