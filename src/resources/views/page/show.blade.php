@extends('druid::layouts.app')

@section('content')
    <h1>{{ $page->title }}</h1>

    {!! $page->content !!}

    <p>Created at: {{ $page->created_at }}</p>
    <p>Updated at: {{ $page->updated_at }}</p>
@endsection
