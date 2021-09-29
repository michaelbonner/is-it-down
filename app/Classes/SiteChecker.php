<?php

namespace App\Classes;

use App\Classes\HttpChecker;
use App\Classes\SslChecker;

class SiteChecker
{
    public static function checkSite($site)
    {
        // Check SSL certificate
        SslChecker::validate_certificate($site);

        // Then check connection
        HttpChecker::check_site_status($site);
    }
}
