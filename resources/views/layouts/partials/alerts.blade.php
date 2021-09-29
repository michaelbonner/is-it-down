@if ($errors->any())
<article class="message is-danger">
    <div class="message-body">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</article>
@endif

@if(session()->has('message'))
<article class="message is-success">
    <div class="message-body">
        {{ session()->get('message') }}
    </div>
</article>
@endif

@if(session()->has('info'))
<article class="message is-warning">
    <div class="message-body">
        {{ session()->get('info') }}
    </div>
</article>
@endif