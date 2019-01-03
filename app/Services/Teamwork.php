<?php

namespace App\Services;

use App\Models\Site;
use Carbon\Carbon;
use GuzzleHttp\Client;

class Teamwork implements HasTasksContract
{
    public function maybe_create_task(
        Site $site,
        string $status_code,
        string $type,
        Carbon $due_date = null
    ) {
        // don't send to teamwork if this is the first time the site is down
        if (
            $site->downs()->latest()->first()
                ->created_at->diffInMinutes(Carbon::now()) < 5
        ) {
            return true;
        }

        $content = 'Site ' . strtoupper($type) . ' Error: ' . $site->url;

        $teamwork_tasks = $this->find_tasks($content);
        if ($teamwork_tasks->count()) {
            return true;
        }

        $this->send_request(
            $content,
            $status_code,
            config('isitdown.assign_main_task'),
            $due_date ?? Carbon::now()->format('Ymd')
        );

        // Send an extra task to a person if it matches the extra_task_url
        if (
            config('isitdown.extra_task_url') &&
            config('isitdown.assign_extra_task')
        ) {
            if (in_array(
                $site->url,
                [
                    config('isitdown.extra_task_url'),
                    substr(config('isitdown.extra_task_url'), 0, -1)
                ]
            )) {
                $this->send_request(
                    $content,
                    $status_code,
                    config('isitdown.assign_extra_task'),
                    $due_date ?? Carbon::now()->format('Ymd')
                );
            }
        }
    }

    public function maybe_complete_task(
        Site $site,
        string $type
    ) {
        $content = 'Site ' . strtoupper($type) . ' Error: ' . $site->url;
        foreach ($this->find_tasks($content) as $task) {
            $this->complete_task($task);
        }
    }

    public static function send_request($content, $description, $assignee, $due_date)
    {
        $client = self::getClient();

        try {
            $response = $client->request(
                'POST',
                config('isitdown.teamwork_url') . 'tasklists/' .
                config('isitdown.task_list_id') . '/tasks.json',
                [
                    'json' => [
                        'todo-item' => [
                            'content' => $content,
                            'description' => $description,
                            'responsible-party-id' => $assignee,
                            'due-date' => $due_date,
                            'start-date' => Carbon::now()->format('Ymd'),
                            'priority' => 'high',
                            'notify' => true,
                            'estimated-minutes' => 20
                        ]
                    ]
                ]
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
            print_r($e->getRequest());
            print_r(
                json_decode(
                    ($e->getRequest())->getBody()
                )
            );
            die;
        }
    }

    public static function find_tasks($content)
    {
        $client = self::getClient();

        $response = $client->request(
            'GET',
            config('isitdown.teamwork_url') . 'tasklists/' .
            config('isitdown.task_list_id') . '/tasks.json'
        );

        $tasks = json_decode($response->getBody());
        if ($tasks->{'todo-items'}) {
            return collect($tasks->{'todo-items'})
                ->filter(function ($todo) use ($content) {
                    return !$todo->completed && $content == $todo->content;
                });
        }

        return collect([]);
    }

    public static function complete_task($task)
    {
        // complete it
        $client = self::getClient();

        $response = $client->request(
            'PUT',
            config('isitdown.teamwork_url') . 'tasks/' .
            $task->id . '/complete.json'
        );
    }

    public static function getClient()
    {
        return new Client([
            'base_uri' => config('isitdown.teamwork_url'),
            'timeout'  => 5.0,
            'auth' => [config('isitdown.teamwork_key'), 'X'],
        ]);
    }
}
