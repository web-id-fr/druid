@extends('druid::layouts.app')

@section('content')
    @foreach($posts as $post)
        <div class="flex flex-col">
            <a href="{{ route('posts.multilingual.show', [
                'post' => $post->slug,
                'lang' => getCurrentLocale()
            ]) }}" class="font-bold text-gray-700 text-2xl">
                {{ $post->title }} - @foreach($post->categories as $category) {{ $category->name }} @endforeach
            </a>
        </div>
    @endforeach

    {{ $posts->links() }}
@endsection
