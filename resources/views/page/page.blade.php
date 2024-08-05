<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hello Bulma!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<section class="section">
    <div class="container">

        @include('druid::includes.main-menu')

        <h1 class="title">
            {{ $page->title }}
        </h1>

        {!! $page->content !!}

        <p><a href="/admin/pages/{{$page->id}}/edit">Edit page</a></p>

    </div>

    @if ($page->translations->count() > 0)
        Translations :
        <ul>
            @foreach($page->translations as $translation)
                <li><a href="{{ $translation->url() }}">{{ $translation->title }}</a></li>
            @endforeach
        </ul>
    @endif
</section>
</body>
</html>
