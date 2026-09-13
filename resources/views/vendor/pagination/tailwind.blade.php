@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" data-pagination-nav="true" class="flex items-center justify-between w-full">

        {{-- Mobile Pagination (Small Screens) --}}
        <div class="flex items-center justify-between w-full sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800/60 dark:text-slate-500 rounded-xl cursor-not-allowed border border-slate-200/80 dark:border-slate-800">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <span>{!! __('pagination.previous') !!}</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-xl hover:border-[#06B6D4] hover:text-[#06B6D4] transition shadow-xs">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <span>{!! __('pagination.previous') !!}</span>
                </a>
            @endif

            <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-xl hover:border-[#06B6D4] hover:text-[#06B6D4] transition shadow-xs">
                    <span>{!! __('pagination.next') !!}</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800/60 dark:text-slate-500 rounded-xl cursor-not-allowed border border-slate-200/80 dark:border-slate-800">
                    <span>{!! __('pagination.next') !!}</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </span>
            @endif
        </div>

        {{-- Desktop / Full Pagination --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full gap-4">

            {{-- Results Counter Text --}}
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-bold text-slate-800 dark:text-white">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-bold text-slate-800 dark:text-white">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-bold text-slate-800 dark:text-white">{{ $paginator->count() }}</span>
                    @endif
                    {!! __('of') !!}
                    <span class="font-bold text-slate-800 dark:text-white">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            {{-- Pagination Navigation Pills --}}
            <div>
                <div class="inline-flex items-center gap-1.5">

                    {{-- Previous Page Chevron --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center justify-center h-8 w-8 text-xs font-medium text-slate-400 dark:text-slate-600 bg-slate-50 dark:bg-slate-900/40 border border-slate-200/70 dark:border-slate-800/70 rounded-xl cursor-not-allowed opacity-50" aria-hidden="true">
                                <i class="fa-solid fa-chevron-left text-[11px]"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center h-8 w-8 text-xs font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-xl hover:border-[#06B6D4] hover:text-[#06B6D4] dark:hover:border-[#06B6D4] dark:hover:text-[#06B6D4] hover:bg-cyan-500/5 transition shadow-xs" aria-label="{{ __('pagination.previous') }}">
                            <i class="fa-solid fa-chevron-left text-[11px]"></i>
                        </a>
                    @endif

                    {{-- Page Number Elements --}}
                    @foreach ($elements as $element)
                        {{-- Ellipsis Dots Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center justify-center h-8 min-w-[32px] px-2 text-xs font-bold text-slate-400 dark:text-slate-500 cursor-default">
                                    {{ $element }}
                                </span>
                            </span>
                        @endif

                        {{-- Array of Page Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center justify-center h-8 min-w-[32px] px-3 text-xs font-extrabold text-white rounded-xl shadow-sm shadow-cyan-500/30 cursor-default transition"
                                              style="background-color: #06B6D4; border: 1px solid #06B6D4;">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center h-8 min-w-[32px] px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-xl hover:border-[#06B6D4] hover:text-[#06B6D4] dark:hover:border-[#06B6D4] dark:hover:text-[#06B6D4] hover:bg-cyan-500/5 transition shadow-xs" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Chevron --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center h-8 w-8 text-xs font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-xl hover:border-[#06B6D4] hover:text-[#06B6D4] dark:hover:border-[#06B6D4] dark:hover:text-[#06B6D4] hover:bg-cyan-500/5 transition shadow-xs" aria-label="{{ __('pagination.next') }}">
                            <i class="fa-solid fa-chevron-right text-[11px]"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center justify-center h-8 w-8 text-xs font-medium text-slate-400 dark:text-slate-600 bg-slate-50 dark:bg-slate-900/40 border border-slate-200/70 dark:border-slate-800/70 rounded-xl cursor-not-allowed opacity-50" aria-hidden="true">
                                <i class="fa-solid fa-chevron-right text-[11px]"></i>
                            </span>
                        </span>
                    @endif

                </div>
            </div>

        </div>
    </nav>
@endif
