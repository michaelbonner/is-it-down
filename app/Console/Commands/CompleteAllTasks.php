<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CompleteAllTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:completeall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete all open tasks on the task tracker';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function completeTasks()
    {
        $tasks = resolve('HasTasks')->find_all_tasks();
        $tasks->each(function ($task) {
            $this->line('Completing task ID: ' . $task['id']);
            $basecamp = resolve('HasTasks');
            $basecamp->complete_task((int) $task['id']);
        });

        $this->info('Completed ' . $tasks->count() . ' tasks');

        if ($tasks->count() > 0) {
            $this->completeTasks();
        }

        return $tasks->count();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->completeTasks();
        return 0;
    }
}
