<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Http\Requests\SiteStoreRequest;
use App\Http\Requests\SiteUpdateRequest;
use App\Services\Basecamp;

class SitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::all();
        return view('site.index', compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('site.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SiteStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiteStoreRequest $request)
    {
        Site::create($request->all());
        return redirect(route('site.index'))
            ->with('message', 'Successfully created site');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $sites
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        $downs = $site->downs()->withTrashed()
            ->orderBy('updated_at', 'DESC')
            ->get();
        return view('site.show', compact('site', 'downs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Site  $sites
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        $users = Basecamp::getUsers();
        return view(
            'site.edit',
            compact(
                'site',
                'users'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SiteUpdateRequest  $request
     * @param  \App\Models\Site  $sites
     * @return \Illuminate\Http\Response
     */
    public function update(SiteUpdateRequest $request, Site $site)
    {
        $site->update($request->all());
        return redirect(route('site.index'))
            ->with('message', 'Successfully updated site');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $sites
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $site->markTasksComplete();
        $site->delete();
        return redirect()->back()->with('message', 'Successfully deleted site');
    }
}
