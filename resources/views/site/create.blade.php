@extends('layouts.master')
@section('title', 'Create a new site')

@section('content')
    <section class="section">
        <div class="container">
            <h1 class="title">
                Create New Checker
            </h1>
            <hr>
        </div>
        <div class="container">
            <p>&nbsp;</p>
        </div>
        <div class="container">
            <form action="{{route('site.store')}}" method="POST">
                @csrf
                <div class="field">
                    <label class="label" for="url">URL</label>
                    <div class="control">
                        <input class="input" name="url" type="text" placeholder="URL" value="{{old('url')}}">
                    </div>
                </div>
                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-link">Create</button>
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