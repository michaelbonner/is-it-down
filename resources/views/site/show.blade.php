@extends('layouts.master')
@section('title', 'Details for ' . $site->url)

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title">
            Details for {{$site->url}}
        </h1>
        <hr>
    </div>
    <div class="container">
        <div class="control">
            <a href="{{route('site.index')}}" class="button is-info">
                Back
            </a>
        </div>
        <p>&nbsp;</p>
    </div>
    <div class="container">
        <p>
            URL: <a class="{{ $site->is_down ? 'has-text-danger' : '' }}" target="_blank" href="{{ $site->url }}">
                {{ $site->url }}
            </a>
        </p>
        @if( $site->is_down )
        <p>
            Last Down: Currently Down
        </p>
        @else
        <p>
            Last Down: {{ $site->last_down ? $site->last_down->deleted_at->diffForHumans() : 'never' }}
        </p>
        @endif
        <p>
            Count times down: {{ $downs->total() }}
        </p>
        <hr>
    </div>
    <div class="container">
        <h3 class="is-size-3">
            Site down occurences
        </h3>
        <table class="table is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>First Down</th>
                    <th>Back Up</th>
                    <th>Time Down</th>
                    <th>Reports</th>
                </tr>
            </thead>
            <tbody>
                @foreach($downs as $down)
                <tr>
                    <td>
                        {{ $down->created_at }}
                    </td>
                    <td>
                        {{ $down->deleted_at ?? 'Currently Down' }}
                    </td>
                    <td>
                        {{ $down->time_down }}
                    </td>
                    <td>
                        <ul>
                            @foreach($down->reports as $report)
                            <li>
                                {{ $report->status }}
                            </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $downs->links() }}
    </div>
</section>
@endsection