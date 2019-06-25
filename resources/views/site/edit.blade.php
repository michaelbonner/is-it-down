@extends('layouts.master')
@section('title', 'Update ' . $site->url)

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title">
            Update {{$site->url}}
        </h1>
        <hr>
    </div>
    <div class="container">
        <p>&nbsp;</p>
    </div>
    <div class="container">
        <form action="{{route('site.update', $site->id)}}" method="POST">
            @csrf
            @method('put')
            <div class="field">
                <label class="label" for="url">URL</label>
                <div class="control">
                    <input class="input" name="url" type="text" placeholder="URL" value="{{$site->url}}">
                </div>
            </div>
            <div class="field">
                <label class="label tooltip" for="assign_task_to"
                    data-tooltip="User must be assigned to the correct project"
                    title="Tooltip on top">Assign Task To:</label>
                <div class="select">
                    <select class="input" name="assign_task_to">
                        <option value="0" @if($site->assign_task_to == '0')
                            selected
                            @endif
                            >Default</option>
                        @foreach ($users as $user)
                        <option value="{{$user->id}}" @if($site->assign_task_to == $user->id)
                            selected
                            @endif
                            >{{ $user->{'first-name'} }} {{ $user->{'last-name'} }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link">Update</button>
                </div>
                <div class="control">
                    <a href="{{route('site.index')}}" class="button is-info">
                        Back
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection