<h3>{{ __('Menu preview')  }}</h3>
<ul>
    @foreach($menu->items as $menuItem)
        <li>
            <a href="{{ $menuItem->url }}" target="{{ $menuItem->target->value }}">{{ $menuItem->label }}</a>

            @if($menuItem->children->isNotEmpty())
                <ul>
                    @foreach($menuItem->children as $childItem)
                        <li>- <a href="{{ $childItem->url }}" target="{{ $childItem->target->value }}">{{ $childItem->label }}</a></li>

                        @if($childItem->children->isNotEmpty())
                            <ul>
                                @foreach($childItem->children as $childItemLevel2)
                                    <li>- - <a href="{{ $childItemLevel2->url }}" target="{{ $childItemLevel2->target->value }}">{{ $childItemLevel2->label }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
