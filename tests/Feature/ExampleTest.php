<?php

namespace Tests\Feature;

use App\Classes\HttpChecker;
use App\Classes\SslChecker;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sslErrorsAreReported()
    {
        $site = factory(Site::class)->create([
            'url' => 'https://self-signed.badssl.com/',
        ]);

        SslChecker::validate_certificate($site);
        $site = $site->fresh();
        $this->assertCount(1, $site->downs);
    }

    /** @test */
    public function httpErrorsAreReported()
    {
        $site = factory(Site::class)->create([
            'url' => 'https://httpstat.us/400',
        ]);

        HttpChecker::check_site_status($site);
        $site = $site->fresh();
        $this->assertCount(1, $site->downs);
    }
}
