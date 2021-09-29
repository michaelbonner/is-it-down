<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Down extends Model
{
    use SoftDeletes;

    protected $guarded = [];


    public $reasons = [
        'ssl' => 'Invalid SSL/certificate expiring soon',
        'http' => 'Server Error',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function getTimeDownAttribute()
    {
        $time = $this->deleted_at ?? Carbon::now();

        return $this->created_at->diff($time)->format('%H:%I:%S');
    }

    public function getReasonAttribute()
    {
        return array_key_exists($this->type, $this->reasons) ?
            $this->reasons[$this->type] :
            'NA';
    }
}
