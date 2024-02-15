@if (isset($mainMenu))
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="https://bulma.io">
                <img src="https://bulma.io/images/bulma-logo.png" width="112" height="28">
            </a>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                @foreach ($mainMenu->items as $menuItem)
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
                            {{$currentLocale->getName()}}
                        </a>

                        <div class="navbar-dropdown">
                            @foreach ($languageSwitcher as $lang)
                                <a href="{{ $lang->url }}" class="navbar-item">
                                    {{$lang->lang->getName()}}
                                </a>
                            @endforeach

                        </div>
                    </div>
                @endif
            </div>

        </div>
    </nav>
@endif
