@extends('layouts.master')
@section('title', 'Is It Down Index')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title">
            Is It Down Checker
        </h1>
        <hr>
    </div>
    <div class="container">
        <a href="{{route('site.create')}}" class="button is-success">
            Add a new site
        </a>
        <a href="{{route('sites-down.index')}}" class="button is-danger is-outlined">
            View Down Sites
        </a>
        <p>&nbsp;</p>
        <div class="columns is-multiline">
            @foreach ($sites->sortBy('pretty_url_with_no_www') as $site)
            <div class="column column is-one-third">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            <a class="{{ $site->is_down ? 'has-text-danger' : '' }}" target="_blank"
                                href="{{$site->url}}">
                                @if($site->is_secure)
                                <i class="fas fa-lock"></i>
                                @endif
                                {{$site->pretty_url}}
                            </a>
                        </p>
                    </header>
                    <div class="card-content">
                        @if( $site->is_down )
                        <div class="content">
                            <p>
                                Last Down: Currently Down
                            </p>
                        </div>
                        @else
                        <div class="content">
                            <p>
                                Last Down:
                                {{ $site->last_down ? $site->last_down->deleted_at->diffForHumans() : 'never' }}
                            </p>
                        </div>
                        @endif
                    </div>
                    <footer class="card-footer">
                        <a href="{{route('site.show', $site->id)}}" class="card-footer-item">History</a>
                        <a href="{{route('site.edit', $site->id)}}" class="card-footer-item">Edit</a>
                        <div class="card-footer-item">
                            <form action="{{route('site.destroy', $site->id)}}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit"
                                    class="button has-text-danger has-background-white js-confirm-delete"
                                    style="border:0">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </footer>
                </div>
                <p>&nbsp;</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
    $(function() {
            $('.js-confirm-delete').click(function(e){
                e.preventDefault();
                if (confirm('Are you sure you want to delete this?')) {
                    $(this).parents('form').submit();
                }
            })
        });
</script>
@endsection