<?php

namespace App\Http\Controllers;

use App\Models\Site;

class SitesDownController extends Controller
{
    public function index()
    {
        $title = 'Sites Currently Down';
        $sites = Site::all()->filter(function ($site) {
            return $site->downs()->count();
        });
        return view('sites-down.index', compact('sites', 'title'));
    }
}
