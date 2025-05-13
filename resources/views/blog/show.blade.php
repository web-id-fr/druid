@extends('druid::layouts.app')

@section('content')
    <section class="section">
        <div class="container">

            <div class="content">
                <h1 class="title">{{ $post->title }}</h1>

                <div class="is-size-7 has-text-grey">
                    <p>{{ $post->created_at->translatedFormat('j F Y') }}</p>
                </div>

                <div class="mt-5">
                    {!! $post->content !!}
                </div>

                <div class="mt-4">
                    <strong>{{ __('Categories') }}:</strong>
                    <div class="tags">
                        @foreach($post->categories as $category)
                            <a href="{{ route('posts.indexByCategory', $category) }}" class="tag is-primary">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
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
