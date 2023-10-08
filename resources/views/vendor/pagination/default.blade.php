@if ($paginator->hasPages())
    <nav>
        <ul class="uk-pagination uk-flex-right" uk-margin>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><a href="#"><span uk-pagination-previous></span></a></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><span uk-pagination-previous></span></a></li>

            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="uk-disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="uk-active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())

                <li><a href="{{$paginator->nextPageUrl() }}"><span uk-pagination-next></span></a></li>

            @else
                <li><a href="#"><span uk-pagination-next></span></a></li>

            @endif
        </ul>
    </nav>
@endif
