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

        // Make sure this sends to Matt as well if it's redolive.com that is down
        if (
            $site->url === 'https://www.redolive.com' ||
            $site->url === 'https://www.redolive.com/'
        ) {
            $this->send_request(
                $content,
                $status_code,
                config('isitdown.assign_ro_task'),
                $due_date ?? Carbon::now()->format('Ymd')
            );
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
        $client = new Client([
            'timeout' => 10,
        ]);

        $response = $client->request(
            'POST',
            'https://teamwork-api.redolive.co/api/tasklists/' .
            config('isitdown.task_list_id') . '/tasks',
            [
                'form_params' => [
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
        );
    }

    public static function find_tasks($content)
    {
        $client = new Client([
            'timeout' => 10,
        ]);

        $response = $client->request(
            'GET',
            'https://teamwork-api.redolive.co/api/tasklists/' .
            config('isitdown.task_list_id') . '/tasks'
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
        $client = new Client([
            'timeout' => 10,
        ]);

        $response = $client->request(
            'GET',
            'https://teamwork-api.redolive.co/api/tasks/' .
            $task->id . '/complete'
        );
    }
}
