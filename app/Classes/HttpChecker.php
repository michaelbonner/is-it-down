<?php

namespace App\Classes;

use GuzzleHttp\Client;

class HttpChecker
{
    public static function check_site_status($site)
    {
        $client = new Client([
            'timeout' => 10,
        ]);

        $url_parts = parse_url($site->url);

        try {
            $response = $client->request('GET', $site->url, [
                'headers' => [
                    'Accept' => 'text/html,application/xhtml+xml,' .
                        'application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                    'Accept-Encoding' =>  'gzip, deflate',
                    'Accept-Language' =>  'en-US,en;q=0.9',
                    'Cache-Control' =>  'no-cache',
                    'Connection' =>  'keep-alive',
                    'Host' =>  $url_parts['host'],
                    'Pragma' =>  'no-cache',
                    'Upgrade-Insecure-Requests' =>  '1',
                    'User-Agent' =>  'Mozilla/5.0 (Macintosh; Intel Mac OS '
                        . 'X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko)' .
                        ' Chrome/67.0.3396.87 Safari/537.36',
                ],
            ]);

            if (
                $response->getStatusCode() < '200' ||
                $response->getStatusCode() >= '300'
            ) {
                $site->currentlyDown($response->getStatusCode(), 'http');
            } else {
                $site->currentlyUp('http');
            }
        } catch (\Exception $exception) {
            $site->currentlyDown(
                self::get_status_code_from_exception($exception),
                'http'
            );
        }
    }

    public static function get_status_code_from_exception(\Exception $exception)
    {
        if (
            method_exists($exception, 'getResponse') &&
            $exception_response = $exception->getResponse()
        ) {
            return $exception_response->getStatusCode();
        } else {
            return 'No Response';
        }
    }
}
