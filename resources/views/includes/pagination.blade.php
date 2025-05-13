@if ($paginator->hasPages())
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        @if ($paginator->onFirstPage())
            <a class="pagination-previous" disabled>{{ __('Previous') }}</a>
        @else
            <a class="pagination-previous" href="{{ $paginator->previousPageUrl() }}">{{ __('Previous') }}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a class="pagination-next" href="{{ $paginator->nextPageUrl() }}">{{ __('Next') }}</a>
        @else
            <a class="pagination-next" disabled>{{ __('Next') }}</a>
        @endif

        <ul class="pagination-list">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="pagination-ellipsis">&hellip;</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a class="pagination-link is-current" aria-current="page">{{ $page }}</a></li>
                        @else
                            <li><a class="pagination-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </nav>
@endif
