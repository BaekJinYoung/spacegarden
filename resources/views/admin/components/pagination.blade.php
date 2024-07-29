@if($paginator->lastPage() >1)
    <div class="pagination col-group">
        @if ($paginator->onFirstPage())
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">
                <i class="xi-angle-left-min"></i>
            </a>
        @endif
        @for ($pageNum = 1; $pageNum <= $paginator->lastPage(); $pageNum++)
            <a href="{{ $paginator->url($pageNum) }}"
               class="page-btn {{ $paginator->currentPage() == $pageNum ? 'active' : '' }}">
                {{ $pageNum }}
            </a>
        @endfor
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">
                <i class="xi-angle-right-min"></i>
            </a>
        @else
        @endif
    </div>
@endif
