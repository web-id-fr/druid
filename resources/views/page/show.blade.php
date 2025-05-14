@extends('druid::layouts.app')

@section('title', $page->meta_title . ' - ' . config('app.name', 'Laravel') )
@section('indexation', $page->indexation)

@section('meta')
    <link rel="canonical" href="{{ $page->canonical }}"/>
    <meta name="description" content="{{ $page->meta_description }}">
    <meta name="keywords" content="{{ $page->meta_keywords }}">
    <meta property="og:title" content="{{$page->opengraph_title}}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $page->url }}">
    <meta property="og:description" content="{{ $page->opengraph_description }}">
    <meta property="og:image" content="{{ $page->opengraph_picture?->url }}"/>
    <meta property="og:image:alt" content="{{ $page->opengraph_picture_alt }}"/>
@endsection

@section('content')
    <section class="section">
        <div class="container">

            <div class="content">
                <h1 class="title">{{ $page->title }}</h1>

                {!! $page->content !!}
            </div>
        </div>
    </section>

@endsection
