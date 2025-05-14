@extends('druid::layouts.app')

@section('title', $post->meta_title . ' - ' . config('app.name', 'Laravel') )
@section('indexation', $post->indexation)

@section('meta')
    <link rel="canonical" href="{{ $post->canonical }}"/>
    <meta name="description" content="{{ $post->meta_description }}">
    <meta name="keywords" content="{{ $post->meta_keywords }}">
    <meta property="og:title" content="{{$post->opengraph_title}}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $post->url }}">
    <meta property="og:description" content="{{ $post->opengraph_description }}">
    <meta property="og:image" content="{{ $post->opengraph_picture?->url }}"/>
    <meta property="og:image:alt" content="{{ $post->opengraph_picture_alt }}"/>
@endsection
@section('content')
    <section class="section">
        <div class="container">

            <div class="content">
                <h1 class="title">{{ $post->title }}</h1>

                <div class="is-size-7 has-text-grey">
                    <p>{{ $post->created_at->translatedFormat('j F Y') }}</p>
                </div>
                <p>
                    @foreach($post->categories as $category)
                        <a href="{{ $category->url() }}"><span class="tag is-primary">{{ $category->name }}</span></a>
                    @endforeach
                </p>

                <div class="mt-5">
                    {!! $post->content !!}
                </div>

                @if ($post->translations->isNotEmpty())
                    <div class="mt-5">
                        <strong>{{ __('Translations') }}:</strong>
                        <ul class="mt-2">
                            @foreach($post->translations as $translation)
                                <li>
                                    <a href="{{ $translation->url() }}">{{ $translation->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

        </div>
    </section>
@endsection
