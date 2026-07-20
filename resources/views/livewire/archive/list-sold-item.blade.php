<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Sold Items</x-slot>

    {{-- TODO:
        1. Filtering of the ff:
            - Toggle whether the sold items have notes or not
    --}}

    {{-- Sold Items Summary (whole collection; independent of the filters below) --}}
    @if (($summary['total_items'] ?? 0) > 0)
        <div class="mb-8" x-data="{ open: true }">
            <div class="flex items-center justify-between px-2 py-4 bg-gray-300 dark:bg-gray-700">
                <h2 class="text-xl text-gray-800 dark:text-gray-200">Sold Items Summary</h2>

                <button
                    type="button"
                    x-on:click="open = !open"
                    x-bind:aria-expanded="open"
                    x-bind:aria-label="open ? 'Hide summary' : 'Show summary'"
                    class="p-1 text-gray-800 transition-colors border border-gray-500 cursor-pointer dark:text-gray-200 dark:border-gray-400 hover:bg-gray-400 dark:hover:bg-gray-600"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="transition-transform duration-300 size-5"
                        x-bind:class="open ? 'rotate-180' : ''"
                        aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </div>

            <div class="grid transition-all duration-500 ease-in-out" x-bind:class="open ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                <div class="overflow-hidden">
            <div class="grid grid-cols-1 gap-3 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                <x-stat-card label="Total items sold" :value="number_format($summary['total_items'])" />
                <x-stat-card label="Total transactions" :value="number_format($summary['total_transactions'])" />
                <x-stat-card label="Total number of years" :value="number_format($summary['total_years'])" />
                <x-stat-card label="Unique brands" :value="number_format($summary['unique_brands'])" />

                <x-stat-card label="Top 3 brands">
                    <ol class="text-sm list-decimal list-inside">
                        @foreach ($summary['top_brands'] as $brand => $brand_count)
                            <li class="truncate" title="{{ $brand }} ({{ $brand_count }})">{{ $brand }} <span class="text-gray-500 dark:text-gray-400">({{ $brand_count }})</span></li>
                        @endforeach
                    </ol>
                </x-stat-card>

                @foreach ([
                    'top_types' => 'Most common types',
                    'top_sell_locations' => 'Top 3 sell method locations',
                    'top_pay_locations' => 'Top 3 payment method locations',
                ] as $rank_key => $rank_label)
                    <x-stat-card :label="$rank_label">
                        @if (count($summary[$rank_key]))
                            <ol class="text-sm list-decimal list-inside">
                                @foreach ($summary[$rank_key] as $rank_name => $rank_count)
                                    <li class="truncate" title="{{ $rank_name }} ({{ $rank_count }})">{{ $rank_name }} <span class="text-gray-500 dark:text-gray-400">({{ $rank_count }})</span></li>
                                @endforeach
                            </ol>
                        @else
                            <p class="text-sm italic text-gray-500 dark:text-gray-400">None</p>
                        @endif
                    </x-stat-card>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-4 mt-6 lg:grid-cols-2">
                @foreach ([
                    'total_items' => 'Total sold items per year',
                    'total_month' => 'Total sold items per month',
                    'total_price' => 'Total price per year',
                    'avg_price' => 'Average price per year',
                    'top_brand' => 'Top selling brand each year',
                    'top_item' => 'Top selling item each year',
                    'tx_year' => 'Total transactions per year',
                    'tx_month' => 'Total transactions per month',
                ] as $chart_key => $chart_title)
                    <div class="p-4 bg-gray-200 rounded-lg shadow dark:bg-gray-800">
                        <h3 class="mb-3 text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $chart_title }}</h3>

                        <div wire:ignore class="relative w-full h-64">
                            <canvas id="sold-items-chart-{{ $chart_key }}" data-chart="{{ json_encode($summary['charts'][$chart_key]) }}"></canvas>
                        </div>
                    </div>
                @endforeach
            </div>
                </div>
            </div>
        </div>
    @endif

    <div>
        <div>
            <h2 class="px-2 py-4 text-xl text-gray-800 bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Search Archives</h2>

            <form
                {{-- Initialize the filters array for each sub-component --}}
                x-data="{ filters: {} }"
                {{-- Capture values from the sub-components (x-forms.input-select etc.) --}}
                x-on:filter-changed.window="filters[$event.detail.key] = $event.detail.value"
                x-on:form-reset.window="filters = {}"
                wire:submit="search_archives"
                class="flex flex-col justify-between gap-6 px-6 py-4 bg-gray-200 border-b-2 border-b-gray-400 dark:border-b-gray-200 md:gap-8 dark:bg-gray-800"
            >
                <x-forms.input-text class="md:mx-6 lg:mx-12" for="search_query" placeholder_text="Type a name of an item..." title_text="Search archives" />

                <label class="py-2 text-2xl text-gray-800 dark:text-gray-200">Advanced Filters</label>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <x-forms.input-datalist
                        :elements="$brands"
                        :elements_label="'archive-brands'"
                        :label="'Brand'"
                        :text_placeholder="'Select brand'"
                        :wire_model="'archive_brands_choice'"
                    />
                    <x-forms.input-datalist
                        :elements="$types"
                        :elements_label="'archive-types'"
                        :label="'Type'"
                        :text_placeholder="'Select type'"
                        :wire_model="'archive_types_choice'"
                    />
                    <x-forms.input-select
                        :elements="$months"
                        :is_animated="true"
                        :label="'Month'"
                        :wire_model="'archive_months_choice'"
                    />
                    <x-forms.input-select
                        :elements="$years"
                        :is_animated="true"
                        :label="'Year'"
                        :wire_model="'archive_years_choice'"
                    />
                    <x-forms.input-select
                        :elements="\App\Enums\PaymentMethods::cases()"
                        :is_animated="true"
                        :label="'Payment Method'"
                        :use_enum_values="true"
                        :wire_model="'archive_pay_methods_choice'"
                    />
                    <x-forms.input-select
                        :elements="\App\Enums\SellMethods::cases()"
                        :is_animated="true"
                        :label="'Sell Method'"
                        :use_enum_values="true"
                        :wire_model="'archive_sell_methods_choice'"
                    />

                    <div x-data="{
                            selectedTags: $wire.entangle('archive_tags_choice'),
                            addToTags(tag) {
                                if (this.selectedTags.includes(tag)) {
                                    const tagIndex = this.selectedTags.indexOf(tag);
                                    this.selectedTags.splice(tagIndex, 1);
                                } else {
                                    this.selectedTags.push(tag);
                                }
                            },
                            clearTags() {
                                this.selectedTags = [];
                            }
                        }"
                        class="grid grid-cols-1 sm:col-span-2 lg:col-span-3"
                    >
                        <label class="text-gray-600 dark:text-gray-200 me-2">Filter by tags</label>
                        <span
                            x-on:click="clearTags"
                            class="w-fit mt-2 text-sm text-blue-500 dark:text-blue-300 hover:cursor-pointer hover:underline"
                            title="Clear selected tags"
                        >
                                Clear tags
                        </span>

                        <ul class="flex flex-wrap gap-2 mt-4 list-none">
                            @foreach ($tags as $tag)
                                <li
                                    x-bind:class="{'bg-gray-300 dark:bg-gray-500 text-gray-800 dark:text-gray-200': selectedTags.includes('{{ $tag }}'), 'text-gray-800 dark:text-gray-200': !selectedTags.includes('{{ $tag }}')}"
                                    x-on:click="addToTags('{{ $tag }}')"
                                    class="px-2 py-1 transition-colors duration-200 border-2 border-gray-800 rounded-xl hover:cursor-pointer dark:border-gray-200"
                                >
                                    {{ $tag }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="flex justify-center gap-3">
                    <input
                        wire:click="reset_archives_form"
                        value="Clear Form"
                        type="button"
                        class="px-4 py-2 text-gray-800 transition duration-300 bg-red-300 cursor-pointer dark:bg-red-600 dark:text-gray-200 hover:bg-red-500 dark:hover:bg-red-700 hover:text-gray-100 dark:hover:text-gray-200"
                    />

                    <button
                        x-on:click.prevent="$wire.call('search_archives', filters)"
                        class="px-4 py-2 text-gray-800 transition duration-300 bg-green-300 cursor-pointer min-w-[130px] dark:bg-green-600 dark:text-gray-200 hover:bg-green-500 dark:hover:bg-green-700 hover:text-gray-100 dark:hover:text-gray-200"
                        type="button"
                    >
                        <span wire:loading.flex wire:target="search_archives">
                            <x-loading-indicator
                                :loader_color_bg="'fill-gray-200'"
                                :loader_color_spin="'fill-gray-200'"
                                :showText="true"
                                :size="4"
                                :text="'Searching'"
                                :text_color="'text-white'"
                            />
                        </span>

                        <span wire:loading.remove wire:target="search_archives">Search</span>
                    </button>
                </div>
            </form>
        </div>

        <div
            x-cloak
            x-data="{
                loading: false,
                view: 'table'
            }"
            class="my-6"
        >
            <div class="flex flex-col gap-2 mb-2 md:flex-row md:items-center md:justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-gray-200">Sold Items</h2>

                <div class="flex flex-wrap items-center gap-x-4">
                    {{-- Sort control --}}
                    <div class="flex flex-wrap items-center gap-1 py-2">
                        <p class="px-2 text-gray-800 dark:text-gray-200">Sort by</p>

                        @foreach (['date_sold' => 'Date', 'price' => 'Price', 'name' => 'Name'] as $sort_option => $sort_label)
                            <button
                                wire:click="apply_sort('{{ $sort_option }}')"
                                class="px-2 py-1 text-sm border transition-colors {{ $sort_field === $sort_option ? 'text-blue-500 border-blue-500 dark:text-blue-300 dark:border-blue-300' : 'text-gray-800 border-gray-300 dark:border-gray-600 dark:text-gray-200 hover:text-blue-800 dark:hover:text-blue-200' }}"
                                title="Sort by {{ $sort_label }}"
                            >
                                {{ $sort_label }}@if ($sort_field === $sort_option)<span aria-hidden="true">{{ $sort_direction === 'asc' ? ' ↑' : ' ↓' }}</span>@endif
                            </button>
                        @endforeach
                    </div>

                    {{-- View toggle --}}
                    <div class="flex items-center py-2">
                        <p class="px-2 text-gray-800 dark:text-gray-200">Toggle view</p>

                    {{-- Table icon --}}
                    <button
                        x-on:click="loading = true; $dispatch('view-changed'); setTimeout(() => { view = 'table'; loading = false; }, 500)"
                        x-bind:class="view === 'table' ? 'text-blue-500 dark:text-blue-300' : 'text-black dark:text-gray-400 dark:hover:text-blue-200 hover:text-blue-800'"
                        x-bind:disabled="view === 'table' || loading"
                        class="p-1.5 border border-gray-300 dark:border-gray-600 transition-colors"
                        title="Toggle table view"
                    >
                        <svg class="size-5" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Toggle table view">
                            {{-- Outer border --}}
                            <rect x="1" y="1" width="14" height="14" rx="1.5" stroke="currentColor" stroke-width="1.25" />
                            {{-- Vertical divider --}}
                            <line x1="6" y1="1" x2="6" y2="15" stroke="currentColor" stroke-width="1.25" />
                            {{-- Horizontal dividers --}}
                            <line x1="1" y1="5.5" x2="15" y2="5.5" stroke="currentColor" stroke-width="1.25" />
                            <line x1="1" y1="10" x2="15" y2="10" stroke="currentColor" stroke-width="1.25" />
                        </svg>
                    </button>

                    {{-- List icon --}}
                    <button
                        x-on:click="loading = true; $dispatch('view-changed'); setTimeout(() => { view = 'list'; loading = false; }, 500)"
                        x-bind:class="view === 'list' ? 'text-blue-500 dark:text-blue-300' : 'text-black dark:text-gray-400 dark:hover:text-blue-200 hover:text-blue-800'"
                        x-bind:disabled="view === 'list' || loading"
                        class="p-1.5 border border-gray-300 dark:border-gray-600 transition-colors"
                        title="Toggle list view"
                    >
                        <svg class="size-5" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Toggle list view">
                        {{-- Three horizontal rows representing a list --}}
                            <rect x="1" y="3"  width="2" height="2" rx="0.5" fill="currentColor" />
                            <rect x="4.5" y="3"  width="10.5" height="2" rx="0.5" fill="currentColor" />
                            <rect x="1" y="7"  width="2" height="2" rx="0.5" fill="currentColor" />
                            <rect x="4.5" y="7"  width="10.5" height="2" rx="0.5" fill="currentColor" />
                            <rect x="1" y="11" width="2" height="2" rx="0.5" fill="currentColor" />
                            <rect x="4.5" y="11" width="10.5" height="2" rx="0.5" fill="currentColor" />
                        </svg>
                    </button>
                    </div>
                </div>
            </div>

            @if ($is_filtered && $sold_items->total() >= 1)
                <p class="px-2 text-green-800 dark:text-green-200">Your search returned {{ $sold_items->total() }} {{ $sold_items->total() == 1 ? 'result' : 'results' }}.</p>
            @endif

            <span x-show="loading">
                <x-loading-indicator
                    :loader_color_bg="'fill-gray-800 dark:fill-gray-200'"
                    :loader_color_spin="'fill-gray-800 dark:fill-gray-200'"
                    :showText="true"
                    :size="4"
                    :text="'Changing view...'"
                    :text_color="'text-gray-800 dark:text-gray-200'"
                />
            </span>

            <div x-show="!loading">
                {{ $sold_items->withQueryString()->links(data: ['scrollTo' => false]) }}
            </div>

            <div x-show="view === 'table' && !loading"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-5"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="grid grid-cols-1 mt-6 gap-x-6 gap-y-10 md:grid-cols-2 xl:grid-cols-3 xl:gap-x-8 min-h-[200px]"
            >
                @forelse ($sold_items as $sold_item)
                    <div>
                        {{-- id is used to identify the element being lazy loaded --}}
                        <div
                            x-data="skeletonLoader()"
                            @destroyed="destroy()"
                            class="relative h-80 w-full xl:w-[384px]"
                            id="sold-item-{{ $sold_item->id }}"
                        >
                            <!-- Skeleton Loader -->
                            <div
                                x-show="!isLoaded"
                                x-transition:leave="transition ease-in duration-500"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute inset-0 rounded-md *:bg-transparent skeleton-loader-lg"
                            >
                                <div class="mb-4 size-full text-center text-3xl content-center">
                                    <x-loading-indicator
                                        :loader_color_bg="'fill-gray-200'"
                                        :loader_color_spin="'fill-gray-200'"
                                        :showText="true"
                                        :size="4"
                                        :text="'Loading...'"
                                        :text_color="'text-white'"
                                    />
                                </div>
                            </div>

                            <!-- Actual Content -->
                            <img
                                x-show="isLoaded"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                src="{{ $sold_item->image_location ? asset('/storage/' .$sold_item->image_location) : asset('/images/no-image-available-placeholder-1920x1080-transparent.svg') }}"
                                class="object-cover transition duration-300 bg-transparent rounded-md aspect-square group-hover:opacity-75 w-full max-h-80 lg:aspect-auto hover:scale-105"
                                @php
                                    $image_text = $sold_item->image_location ? 'Image of ' .$sold_item->item_name : 'No image found for ' .$sold_item->item_name;
                                @endphp
                                alt="{{ $image_text }}"
                                title="{{ $image_text }}"
                                loading="lazy"
                            />
                        </div>

                        <div class="flex flex-col mt-4">
                            <div class="flex justify-between gap-2">
                                <h3 class="text-lg text-gray-800 grow shrink basis-0 dark:text-gray-200 md:truncate" title="{{ $sold_item->item_name }}">{{ $sold_item->item_name }}@if (str_contains(strtolower($sold_item->tags ?? ''), 'hot item')) <span class="text-base leading-none" title="Has more than 10 inquiries!">&#128293;</span>@endif</h3>
                                <p class="text-lg text-gray-800 justify-self-end dark:text-gray-200" title="Price">&#8369; {{ $sold_item->price }}</p>
                            </div>

                            <div class="flex justify-between gap-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" title="Date sold">{{ Carbon\Carbon::parse($sold_item->date_sold)->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500 justify-self-end dark:text-gray-400" title="Size">{{ $sold_item->size }}</p>
                            </div>

                            <div class="flex justify-between gap-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" title="Condition">{{ $sold_item->condition }}</p>
                                <p class="text-sm justify-self-end text-gray-500 max-w-full dark:text-gray-400 md:truncate md:max-w-50 {{ !$sold_item->tags ? 'italic' : '' }}" title="{{ $sold_item->tags }}">{{ $sold_item->tags ? $sold_item->tags : 'No tags' }}</p>
                            </div>

                            <div class="flex justify-between gap-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" title="Pay method">{{ $sold_item->pay_method->method }}</p>
                                <p class="text-sm justify-self-end text-gray-500 max-w-full dark:text-gray-400 md:truncate md:max-w-50" title="Remittance location">{{ $sold_item->pay_method->remittance_location }}</p>
                            </div>

                            <div class="flex justify-between gap-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" title="Sell method">{{ $sold_item->sell_method->method }}</p>
                                <p class="text-sm justify-self-end text-gray-500 max-w-full dark:text-gray-400 md:truncate md:max-w-50" title="Sell location">{{ $sold_item->sell_method->location }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-1 px-2 text-red-800 dark:text-red-200 md:col-span-2 xl:col-span-3">No sold items found for this filter. Please adjust your filters.</p>
                @endforelse
            </div>

            <div
                x-show="view === 'list' && !loading"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-5"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                @forelse ($sold_items as $sold_item)
                    <div class="flex items-center justify-between gap-2 px-2 py-4 delay-120ms">
                        <div
                            x-data="skeletonLoader()"
                            @destroyed="destroy()"
                            class="relative size-[74.5px]"
                            id="sold-item-{{ $sold_item->id }}"
                        >
                            <!-- Skeleton Loader -->
                            <div
                                x-show="!isLoaded"
                                x-transition:leave="transition ease-in duration-500"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute inset-0 rounded-md *:bg-transparent skeleton-loader"
                            >
                                <div class="mb-4 size-full text-center text-3xl content-center">
                                    <x-loading-indicator
                                        :loader_color_bg="'fill-gray-200'"
                                        :loader_color_spin="'fill-gray-200'"
                                        :showText="false"
                                        :size="4"
                                    />
                                </div>
                            </div>

                            <!-- Actual Content -->
                            <img
                                x-show="isLoaded"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                src="{{ $sold_item->image_location ? asset('/storage/' .$sold_item->image_location) : asset('/images/no-image-available-placeholder-1920x1080-transparent.svg') }}"
                                class="object-cover transition duration-300 bg-transparent rounded-md aspect-square group-hover:opacity-75 w-full h-20 lg:aspect-auto hover:scale-105"
                                @php
                                    $image_text = $sold_item->image_location ? 'Image of ' .$sold_item->item_name : 'No image found for ' .$sold_item->item_name;
                                @endphp
                                alt="{{ $image_text }}"
                                title="{{ $image_text }}"
                                loading="lazy"
                            />
                        </div>

                        <div class="w-full flex flex-col gap-0.5">
                            <h3 class="text-lg text-gray-800 border-b border-b-gray-500 dark:border-b-gray-400 dark:text-gray-200 md:truncate" title="{{ $sold_item->item_name }}">{{ $sold_item->item_name }}@if (str_contains(strtolower($sold_item->tags ?? ''), 'hot item')) <span class="text-base leading-none" title="Hot item">&#128293;</span>@endif</h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span title="Price">&#8369; {{ $sold_item->price }}</span>
                                <span class="px-1 text-gray-500 dark:text-gray-400">&nbsp;·&nbsp;</span>
                                <span title="Date sold">{{ Carbon\Carbon::parse($sold_item->date_sold)->format('M d, Y') }}</span>
                                <span class="px-1 text-gray-500 dark:text-gray-400">&nbsp;·&nbsp;</span>
                                <span title="Size">{{ $sold_item->size }}</span>
                                <span class="px-1 text-gray-500 dark:text-gray-400">&nbsp;·&nbsp;</span>
                                <span title="Condition">{{ $sold_item->condition }}</span>
                                <span class="px-1 text-gray-500 dark:text-gray-400">&nbsp;·&nbsp;</span>
                                <span title="Pay method">{{ $sold_item->pay_method->method }}: <span title="Remittance location">{{ $sold_item->pay_method->remittance_location }}</span></span>
                                <span class="px-1 text-gray-500 dark:text-gray-400">&nbsp;·&nbsp;</span>
                                <span title="Sell method">{{ $sold_item->sell_method->method }}: <span title="Sell location">{{ $sold_item->sell_method->location }}</span></span>
                                <p class="text-sm justify-self-end text-gray-500 max-w-full dark:text-gray-400 {{ !$sold_item->tags ? 'italic' : '' }}" title="{{ $sold_item->tags }}">{{ $sold_item->tags ? $sold_item->tags : 'No tags' }}</p>
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="col-span-1 px-2 text-red-800 dark:text-red-200 md:col-span-2 xl:col-span-3">No sold items found for this filter. Please adjust your filters.</p>
                @endforelse
            </div>

            <div x-show="!loading">
                {{ $sold_items->withQueryString()->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>

{{-- Custom scripts --}}
@push('scripts')
    @vite(['resources/js/skeleton-loading.js', 'resources/js/sold-items-chart.js'])
@endpush
