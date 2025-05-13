@extends('druid::layouts.app')

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
