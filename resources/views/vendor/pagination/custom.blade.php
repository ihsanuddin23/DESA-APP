@if ($paginator->hasPages())
    <nav class="pagination-simple">

        {{-- Info: Page X of Y --}}
        <div class="ps-info">
            Halaman <strong>{{ $paginator->currentPage() }}</strong> dari {{ $paginator->lastPage() }}
        </div>

        {{-- Controls --}}
        <div class="ps-controls">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <button class="ps-btn ps-btn-disabled" disabled>
                    <i class="bi bi-chevron-left"></i>
                    <span>Sebelumnya</span>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="ps-btn">
                    <i class="bi bi-chevron-left"></i>
                    <span>Sebelumnya</span>
                </a>
            @endif

            {{-- Page Numbers --}}
            <div class="ps-pages">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="ps-gap">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="ps-page ps-page-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="ps-page">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="ps-btn">
                    <span>Selanjutnya</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <button class="ps-btn ps-btn-disabled" disabled>
                    <span>Selanjutnya</span>
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif
        </div>

    </nav>
@endif
