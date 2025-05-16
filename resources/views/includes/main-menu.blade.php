@if (isset($menus['main-menu']))
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <h1 class="title m-5"><a href="/">{{ config('app.name') }}</a></h1>
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                @foreach ($menus['main-menu']->items as $menuItem)
                    @if ($menuItem->children && $menuItem->children->isNotEmpty())
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a href="#" class="navbar-link">
                                {{$menuItem->label}}
                            </a>

                            <div class="navbar-dropdown">
                                @foreach ($menuItem->children as $subMenuItem)
                                    <a href="{{ $subMenuItem->url }}" target="{{$subMenuItem->target->getHtmlProperty()}}" class="navbar-item">
                                        {{$subMenuItem->label}}
                                    </a>
                                @endforeach

                            </div>
                        </div>
                    @else
                        <a href="{{ $menuItem->url }}" target="{{$menuItem->target->getHtmlProperty()}}" class="navbar-item">
                            {{$menuItem->label}}
                        </a>
                    @endif
                @endforeach

                @if (isset($languageSwitcher))
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a href="#" class="navbar-link">
                            {{\Webid\Druid\Facades\Druid::getCurrentLocaleKey()}}
                        </a>

                        <div class="navbar-dropdown">
                            @foreach ($languageSwitcher as $lang)
                                <a href="{{ $lang['url'] }}" class="navbar-item">
                                    {{$lang['label']}}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </nav>
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const burger = document.querySelector('.navbar-burger');
        const menu = document.getElementById(burger.dataset.target);

        burger.addEventListener('click', () => {
            burger.classList.toggle('is-active');
            menu.classList.toggle('is-active');
        });
    });
</script>
