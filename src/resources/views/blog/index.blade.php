@extends('druid::layouts.app')

@section('content')
    @foreach($posts as $post)
        <div class="flex flex-col">
            <div class="font-bold text-gray-700 text-2xl">
                {{ $post->title }} - @foreach($post->categories as $category) {{ $category->name }} @endforeach
            </div>
        </div>
    @endforeach
@endsection
