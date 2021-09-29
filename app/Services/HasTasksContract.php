<?php

namespace App\Services;

use App\Models\Site;
use Carbon\Carbon;

interface HasTasksContract
{
    /*
     * Create a task if necessary
     */
    public function maybe_create_task(
        Site $site,
        string $status_code,
        string $type,
        Carbon $due_date = null
    );

    /*
     * Complete a task if necessary
     */
    public function maybe_complete_task(
        Site $site,
        string $type
    );
}
