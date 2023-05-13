<?php

namespace App\Classes;

use Spatie\SslCertificate\SslCertificate;

class SslChecker
{
    public static function validate_certificate($site)
    {
        try {
            $url_parts = parse_url($site->url);

            if (substr($site->url, 0, 5) == 'https') {
                $certificate = SslCertificate::createForHostName(
                    $url_parts['host'],
                    10,
                );

                // Check if certificate is valid
                if (!$certificate->isValid()) {
                    $site->currentlyDown('Invalid SSL Certificate', 'ssl');

                    return;
                }

                // If certificate is expiring within 15 days,
                // don't report it as down. Just make a task.
                if ($certificate->expirationDate()->diffInDays() < 15) {
                    $site->willBeDownSoon(
                        'SSL Certificate Expiring Soon',
                        'ssl',
                        $certificate->expirationDate()->subWeekday()
                    );

                    return;
                }

                $site->currentlyUp('ssl');
            }
        } catch (\Spatie\SslCertificate\Exceptions\CouldNotDownloadCertificate $e) {
            $site->currentlyDown('Invalid SSL Certificate', 'ssl');
        } catch (\Exception $e) {
            $site->currentlyDown('Unknown SSL Exception', 'ssl');
        }
    }
}
