<?php

namespace App\Services;

use App\Models\BasecampAuth;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Basecamp implements HasTasksContract
{
    public static function getToken()
    {
        return BasecampAuth::latest()
            ->first()
            ->data['access_token'];
    }

    public static function getUsers()
    {
        $response = Http::withToken(self::getToken())
            ->withHeaders([
                'User-Agent' => config('services.basecamp.useragent'),
            ])
            ->get(
                'https://3.basecampapi.com/' . config('services.basecamp.account') .
                    '/projects/' . config('services.basecamp.project') . '/people.json'
            );

        return collect(
            $response->json()
        );
    }

    public function maybe_create_task(
        Site $site,
        string $status_code,
        string $type,
        Carbon $due_date = null
    ) {
        // don't create a task if it hasn't been down for at least 5 minutes
        if (
            $site->downs()
            ->latest()
            ->first()
            ->created_at
            ->diffInMinutes(
                Carbon::now()
            ) < 5

        ) {
            return true;
        }

        $content = 'Site ' . strtoupper($type) . ' Error: ' . $site->url;

        $tasks = $this->find_tasks($content);
        if ($tasks->count()) {
            return true;
        }

        $data = [
            'content' => $content,
            'due_on' => $due_date ?? Carbon::now()->format('Ymd'),
            'notify' => true,
        ];

        if ($site->assign_task_to) {
            $data['assignee_ids'] = [
                $site->assign_task_to,
            ];
        }

        $response = Http::withToken(self::getToken())
            ->withHeaders([
                'User-Agent' => config('services.basecamp.useragent'),
            ])
            ->post(
                'https://3.basecampapi.com/' . config('services.basecamp.account') .
                    '/buckets/' . config('services.basecamp.project') .
                    '/todolists/' . config('services.basecamp.list') .
                    '/todos.json',
                $data
            );

        return $response->json();
    }

    public function maybe_complete_task(
        Site $site,
        string $type
    ) {
        $content = 'Site ' . strtoupper($type) . ' Error: ' . $site->url;
        foreach ($this->find_tasks($content) as $task) {
            return $this->complete_task((int) $task['id']);
        }
    }

    public function complete_task(
        int $taskId
    ) {
        $response = Http::withToken(self::getToken())
            ->withHeaders([
                'User-Agent' => config('services.basecamp.useragent'),
            ])
            ->post(
                'https://3.basecampapi.com/' . config('services.basecamp.account') .
                    '/buckets/' . config('services.basecamp.project') .
                    '/todos/' . $taskId .
                    '/completion.json',
                []
            );

        return $response->json();
    }

    public static function find_tasks($content)
    {
        $response = Http::withToken(self::getToken())
            ->withHeaders([
                'User-Agent' => config('services.basecamp.useragent'),
            ])
            ->get(
                'https://3.basecampapi.com/' . config('services.basecamp.account') .
                    '/buckets/' . config('services.basecamp.project') .
                    '/todolists/' . config('services.basecamp.list') .
                    '/todos.json'
            );

        $tasks = collect(
            $response->json()
        );

        if ($tasks->count()) {
            return $tasks->filter(function ($task) use ($content) {
                return $content == $task['content'];
            });
        }

        return collect([]);
    }

    public static function find_all_tasks()
    {
        $response = Http::withToken(self::getToken())
            ->withHeaders([
                'User-Agent' => config('services.basecamp.useragent'),
            ])
            ->get(
                'https://3.basecampapi.com/' . config('services.basecamp.account') .
                    '/buckets/' . config('services.basecamp.project') .
                    '/todolists/' . config('services.basecamp.list') .
                    '/todos.json'
            );

        $tasks = collect(
            $response->json()
        );

        return $tasks;
    }
}
