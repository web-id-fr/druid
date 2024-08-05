@extends('druid::layouts.app')

@section('content')
    <div class="flex flex-col gap-12">
        <div class="flex flex-row">
            @foreach($categories as $category)
                @if(\Webid\Druid\Facades\Druid::isMultilingualEnabled())
                    <a href="{{ route('posts.multilingual.indexByCategory', [
                    'category' => $category->slug,
                    'lang' => \Webid\Druid\Facades\Druid::getCurrentLocale()
                ]) }}" class="font-bold text-gray-700 text-2xl">
                        {{ $category->name }}
                    </a>
                @else
                    <a href="{{ route('posts.indexByCategory', [
                    'category' => $category->slug,
                ]) }}" class="font-bold text-gray-700 text-2xl">
                        {{ $category->name }}
                    </a>
                @endif
            @endforeach
        </div>
        @foreach($posts as $post)
            <div class="flex flex-col">
                @if(\Webid\Druid\Facades\Druid::isMultilingualEnabled())
                    <a href="{{ route('posts.multilingual.show', [
                'post' => $post->slug,
                'category' => $post->categories->first()->slug,
                'lang' => \Webid\Druid\Facades\Druid::getCurrentLocale()
            ]) }}" class="font-bold text-gray-700 text-2xl">
                        {{ $post->title }} - @foreach($post->categories as $category) {{ $category->name }} @endforeach
                    </a>
                @else
                    <a href="{{ route('posts.show', [
                'post' => $post->slug,
                'category' => $post->categories->first()->slug
            ]) }}" class="font-bold text-gray-700 text-2xl">
                        {{ $post->title }} - @foreach($post->categories as $category) {{ $category->name }} @endforeach
                    </a>
                @endif
            </div>
        @endforeach

        {{ $posts->links() }}
    </div>

@endsection
