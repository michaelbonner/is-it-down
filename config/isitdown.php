<?php

return [
    'teamwork_url' => str_finish(env('TEAMWORK_URL'), '/'),
    'teamwork_key' => env('TEAMWORK_KEY'),
    'assign_main_task' => env('ASSIGN_MAIN_TASK'),
    'assign_extra_task' => env('ASSIGN_EXTRA_TASK'),
    'extra_task_url' => str_finish(env('EXTRA_TASK_URL'), '/'),
    'task_list_id' => env('TASK_LIST_ID'),
];
