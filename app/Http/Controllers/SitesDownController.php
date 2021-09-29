<?php

namespace App\Http\Controllers;

use App\Models\Site;

class SitesDownController extends Controller
{
    public function index()
    {
        $title = 'Sites Currently Down';
        $sites = Site::with([
            'downs',
            'downsWithTrashed',
            'reports',
        ])
            ->whereHas('downs')
            ->get();

        return view(
            'sites-down.index',
            compact('sites', 'title')
        );
    }
}
