<?php

namespace App\Models;

use App\Models\Down;
use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function downs()
    {
        return $this->hasMany(Down::class);
    }
    
    // get whether or not the site is currently reported as down
    public function getIsDownAttribute()
    {
        return $this->downs()->count();
    }

    public function getLastDownAttribute()
    {
        $last_down = $this->downs()->withTrashed()->latest()->first();
        return $last_down ?? false;
    }

    public function getHostNameAttribute()
    {
        $url_parts = parse_url($this->url);
        return $url_parts['host'] ?? null;
    }

    public function getPrettyUrlAttribute()
    {
        $url_parts = parse_url($this->url);
        $path = $url_parts['path'] ?? null;
        return $this->host_name . $path;
    }

    public function getPrettyUrlWithNoWwwAttribute()
    {
        return str_replace('www.', '', $this->pretty_url);
    }

    public function getIsSecureAttribute()
    {
        $url_parts = parse_url($this->url);
        return $url_parts['scheme'] == 'https';
    }

    public function willSoonBeDown(
        $status = 'na',
        $type,
        $due_date
    ) {
        resolve('HasTasks')->maybe_create_task($this, $status, $type, $due_date);
    }

    /**
     * Mark site as currently down.
     * If site is already marked down,
     * check if this status has already been reported.
     */
    public function currentlyDown(
        $status = 'na',
        $type
    ) {
        // Create a down if there isn't one already
        if (! $down = Down::where('site_id', $this->id)->first()) {
            $down = Down::create([
                'site_id' => $this->id,
                'type' => $type
            ]);
        }

        // Add a report to this down for this status if there isn't one already
        if (
            ! $report = Report::where('down_id', $down->id)
                                ->where('status', $status)
                                ->first()
        ) {
            Report::create([
                'down_id' => $down->id,
                'status' => $status
            ]);
        }

        resolve('HasTasks')->maybe_create_task($this, $status, $type);
    }

    // mark site as currently up
    public function currentlyUp(string $type)
    {
        $this->downs()->where('type', $type)->delete();
        resolve('HasTasks')->maybe_complete_task($this, $type);
    }

    public function markTasksComplete()
    {
        resolve('HasTasks')->maybe_complete_task($this, 'http');
        resolve('HasTasks')->maybe_complete_task($this, 'ssl');
    }
}
