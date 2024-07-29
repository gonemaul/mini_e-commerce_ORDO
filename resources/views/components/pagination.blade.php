<link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">

@if ($paginator->hasPages())
    <div class="pagination">
        @if ($paginator->onFirstPage())
            <button class="button" id="startBtn" disabled>
                <i class="fa-solid fa-angles-left"></i>
            </button>
            <button class="button prevNext" id="prev" disabled>
                <i class="fa-solid fa-angle-left"></i>
            </button>
        @else
            <a href="{{ $paginator->url(1) }}">
                <button class="button" id="startBtn">
                    <i class="fa-solid fa-angles-left"></i>
                </button>
            </a>
            <a href="{{ $paginator->previousPageUrl() }}">
                <button class="button prevNext" id="prev">
                    <i class="fa-solid fa-angle-left"></i>
                </button>
            </a>
        @endif

        <div class="links">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <a href="#" class="link active">{{ $element }}</a>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a href="#" class="link active">{{ $page }}</a>
                        @else
                            <a href="{{ $url }}" class="link">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>


        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">
                <button class="button prevNext" id="next">
                    <i class="fa-solid fa-angle-right"></i>
                </button>
            </a>
            <a href="{{ $paginator->url($paginator->lastPage()) }}">
                <button class="button" id="endBtn">
                    <i class="fa-solid fa-angles-right"></i>
                </button>
            </a>
        @else
            <button class="button prevNext" id="next" disabled>
                <i class="fa-solid fa-angle-right"></i>
            </button>
            <button class="button" id="endBtn" disabled>
                <i class="fa-solid fa-angles-right"></i>
            </button>
        @endif

    </div>
@endif
