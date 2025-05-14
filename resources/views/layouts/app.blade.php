<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">

    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta name="robots" content="@yield('indexation', config('cms.disable_robots_follow') === true ? 'nofollow,noindex': 'follow,index')">


    @yield('meta')
</head>
<body>
<div class="container">
    @include('druid::includes.main-menu')

    @yield('content')

    @include('druid::includes.footer')
</div>
</body>
</html>
