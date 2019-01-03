<?php
namespace App\Traits;

use Carbon\Carbon;
use GuzzleHttp\Client;

trait Reportable
{
    public static function create_task(
        $site,
        $type,
        $status,
        $due_date = false,
        $message = false
    ) {
        $content = 'Site ' . strtoupper($type) . ' Error: ' . $site->url;

        $teamwork_task = self::find_task($content);
        if ($teamwork_task) {
            return true;
        }

        if (! $due_date) {
            $due_date = Carbon::now()->format('Ymd');
        }

        $description = $status;

        if ($message) {
            $description .= ' -- Message: ' . $message;
        }

        // don't send to teamwork if this is the first time the site is down
        if (!$site->downs()->latest()->first()) {
            return true;
        }

        self::send_request(
            $content,
            $description,
            config('isitdown.assign_main_task'),
            $due_date
        );
        
        // Make sure this sends to Matt as well if it's redolive.com that is down
        if (
            $site->url === 'https://www.redolive.com' ||
            $site->url === 'https://www.redolive.com/'
        ) {
            self::send_request(
                $content,
                $description,
                config('isitdown.assign_ro_task'),
                $due_date
            );
        }
    }

    public static function send_request($content, $description, $assignee, $due_date)
    {
        $client = new Client([
            'timeout'  => 10,
        ]);

        $response = $client->request(
            'POST',
            'https://teamwork-api.redolive.co/api/tasklists/769948/tasks',
            [
                'form_params' => [
                    'content' => $content,
                    'description' => $description,
                    'responsible-party-id' => $assignee,
                    'due-date' => $due_date,
                    'start-date' => Carbon::now()->format('Ymd'),
                    'priority' => 'high',
                    'notify' => true
                ]
            ]
        );
    }

    public static function find_task($content)
    {
        $client = new Client([
            'timeout'  => 10,
        ]);

        $response = $client->request(
            'GET',
            'https://teamwork-api.redolive.co/api/tasklists/769948/tasks'
        );

        $tasks = json_decode($response->getBody());
        if ($tasks->{'todo-items'}) {
            foreach ($tasks->{'todo-items'} as $task) {
                if (! $task->completed && $task->content == $content) {
                    return $task;
                }
            }
        }

        return false;
    }

    public static function complete_task($site, $type)
    {
        // find the task
        $teamwork_task = self::find_task(
            'Site ' . strtoupper($type) . ' Error: ' . $site->url
        );
        if (! $teamwork_task) {
            return false;
        }

        // complete it
        $client = new Client([
            'timeout'  => 10,
        ]);

        $response = $client->request(
            'GET',
            'https://teamwork-api.redolive.co/api/tasks/' .
            $teamwork_task->id . '/complete'
        );
    }

    public static function maybe_report_url_fixed($site, $type)
    {
        if (! $site->is_down) {
            return;
        }

        $site->currentlyUp($type);
        self::complete_task($site, $type);
    }
}
