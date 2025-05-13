@extends('druid::layouts.app')

@section('content')
    <section class="section">
        <div class="container">

            <div class="columns">
                <div class="column is-four-fifths">
                    @foreach($posts as $post)
                        <div class="card mb-3">
                            <div class="card-content">

                                <div class="media">
                                    <div class="media-content">
                                        @if ($post->thumbnail)
                                            <div class="w-64 aspect-video">
                                                <a href="{{ $post->url()  }}">
                                                    <x-curator-glider
                                                        class="object-cover w-auto"
                                                        :media="$post->thumbnail_id"
                                                    />
                                                </a>
                                            </div>
                                        @endif
                                        <div class="is-size-7 has-text-grey">
                                            <p>{{ $post->created_at->translatedFormat('j F Y') }}</p>
                                        </div>
                                        <p>
                                            @foreach($post->categories as $category)
                                                <a href="{{ $category->url() }}"><span class="tag is-primary">{{ $category->name }}</span></a>
                                            @endforeach
                                        </p>
                                        <p class="title is-4"><a href="{{ $post->url()  }}">{{ $post->title }}</a></p>
                                    </div>
                                </div>

                                <div class="content">
                                    <p>{{ $post->excerpt() }}</p>
                                    <p><a href="{{ $post->url() }}">{{ __('Read more') }}</a></p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{ $posts->links('druid::includes.pagination') }}
                </div>
                <div class="column">
                    <div class="card">
                        <div class="card-content">
                            <div class="media">
                                <div class="media-content">
                                    <p class="title is-4">{{__('Categories')}}</p>
                                </div>
                            </div>

                            <div class="content">
                                <ul>
                                    @foreach($categories as $category)
                                        <li>
                                            <a href="{{ $category->url() }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
