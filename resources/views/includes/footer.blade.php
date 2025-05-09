<div class="block">
    @if (isset($menus['footer-menu']))
        <nav class="navbar" role="navigation" aria-label="footer navigation">
            <div id="navbar-footer" class="navbar-menu">
                <div class="navbar-start">
                    @foreach ($menus['footer-menu']->items as $menuItem)
                        <a href="{{ $menuItem->url }}" target="{{$menuItem->target->getHtmlProperty()}}" class="navbar-item">
                            {{$menuItem->label}}
                        </a>
                    @endforeach
                </div>

            </div>
        </nav>
    @endif

    <p>© {{date('Y')}} {{config('app.name')}}</p>

</div>
