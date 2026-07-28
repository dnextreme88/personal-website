@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

@if ($paginator->hasPages())
    <div class="my-8">
        <nav role="navigation" aria-label="Pagination Navigation" class="@container flex items-center justify-between">
            <div class="@max-[700px]:flex justify-between flex-1 @[701px]:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <x-button-next-previous as="button" is_disabled="true" text="&larr; Previous" class="h-12" />
                    @else
                        <x-button-next-previous
                            as="button"
                            text="&larr; Previous"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                        />
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <x-button-next-previous
                            as="button"
                            text="Next &rarr;"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                        />
                    @else
                        <x-button-next-previous as="span" is_disabled="true" text="Next &rarr;" class="h-12" />
                    @endif
                </span>
            </div>

            <div class="@max-[700px]:hidden @[701px]:flex-1 sm:flex sm:items-center sm:justify-center md:justify-between md:gap-2">
                <div class="hidden md:flex">
                    <p class="text-sm leading-5 text-gray-700 dark:text-gray-400">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rounded-md shadow-sm rtl:flex-row-reverse">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <x-button-next-previous as="button" is_disabled="true" text="&lt;" class="h-12" />
                            @else
                                <x-button-next-previous
                                    as="button"
                                    text="&lt;"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                />
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span
                                        class="
                                            border
                                            border-gray-300
                                            dark:border-gray-600
                                            bg-white
                                            dark:bg-gray-800
                                            text-cyan-700
                                            dark:text-gray-200
                                            relative
                                            inline-flex
                                            items-center
                                            px-4
                                            py-2
                                            h-12
                                            text-sm
                                            font-medium
                                        "
                                    >
                                        {{ $element }}
                                    </span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span
                                                    class="
                                                        relative
                                                        border
                                                        border-cyan-700
                                                        dark:border-cyan-300
                                                        bg-cyan-200
                                                        dark:bg-cyan-800
                                                        text-cyan-800
                                                        dark:text-cyan-200
                                                        inline-flex
                                                        items-center
                                                        px-4
                                                        py-2
                                                        h-12
                                                        text-sm
                                                        font-medium
                                                    "
                                                    title="You are currently here"
                                                    aria-label="You are currently in this page"
                                                >
                                                    {{ $page }}
                                                </span>
                                            </span>
                                        @else
                                            <button
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                                class="
                                                    relative
                                                    border
                                                    border-gray-300
                                                    dark:border-gray-600
                                                    hover:border-cyan-700
                                                    dark:hover:border-cyan-300
                                                    bg-white
                                                    dark:bg-gray-800
                                                    hover:bg-cyan-200
                                                    dark:hover:bg-cyan-800
                                                    text-cyan-700
                                                    dark:text-gray-200
                                                    hover:text-cyan-800
                                                    dark:hover:text-cyan-200
                                                    inline-flex
                                                    items-center
                                                    px-4
                                                    py-2
                                                    h-12
                                                    text-sm
                                                    font-medium
                                                    cursor-pointer
                                                    transition
                                                    duration-150
                                                    ease-in-out
                                                    hover:z-2
                                                "
                                                title="Go to page {{ $page }}"
                                                type="button"
                                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                            >
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <x-button-next-previous
                                    as="button"
                                    text="&gt;"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                />
                            @else
                                <x-button-next-previous as="button" is_disabled="true" text="&gt;" class="h-12" />
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    </div>
@endif
