<?php

namespace App\Http\Controllers;

use App\Models\BasecampAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BasecampAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->user()) {
            return 'You must be logged in';
        }
        return redirect()
            ->to(
                'https://launchpad.37signals.com/authorization/new?type=web_server&client_id=' .
                    config('services.basecamp.client_id') .
                    '&redirect_uri=' . route('basecamp-verify.store')
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $tokenRequest = Http::post('https://launchpad.37signals.com/authorization/token?type=web_server' .
                '&client_id=' . config('services.basecamp.client_id') .
                '&redirect_uri=' . route('basecamp-verify.store') .
                '&client_secret=' . config('services.basecamp.client_secret') .
                '&code=' . $request->code);

            if (!$tokenRequest->successful()) {
                return redirect()
                    ->to('/')
                    ->with(
                        'info',
                        $tokenRequest->json()['error']
                    );
            }

            BasecampAuth::create([
                'user_id' => $request->user()->id,
                'data' => $tokenRequest->json(),
            ]);

            return redirect()
                ->to('/')
                ->with(
                    'message',
                    'Successfully authenticated with Basecamp'
                );
        } catch (\Throwable $th) {
            report($th);
            return redirect()
                ->to('/')
                ->with(
                    'info',
                    $th->getMessage()
                );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BasecampAuth  $basecampAuth
     * @return \Illuminate\Http\Response
     */
    public function show(BasecampAuth $basecampAuth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BasecampAuth  $basecampAuth
     * @return \Illuminate\Http\Response
     */
    public function edit(BasecampAuth $basecampAuth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BasecampAuth  $basecampAuth
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BasecampAuth $basecampAuth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BasecampAuth  $basecampAuth
     * @return \Illuminate\Http\Response
     */
    public function destroy(BasecampAuth $basecampAuth)
    {
        //
    }
}
