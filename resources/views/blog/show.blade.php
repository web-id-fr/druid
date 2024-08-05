@extends('druid::layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>

    {!! $post->content !!}

    <p>Created at: {{ $post->created_at }}</p>
    <p>Updated at: {{ $post->updated_at }}</p>

    @if ($post->translations->count() > 0)
        Translations :
        <ul>
            @foreach($post->translations as $translation)
                <li><a href="{{ $translation->url() }}">{{ $translation->title }}</a></li>
            @endforeach
        </ul>
    @endif
@endsection
