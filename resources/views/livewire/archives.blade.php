<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Sold Items</x-slot>

    {{-- TODO:
        1. Filtering of the ff:
            - Toggle whether the sold items have notes or not
        2. Display a way to switch between table and compact versions
    --}}

    <div>
        <div>
            <h2 class="px-2 py-4 text-xl text-gray-800 bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Search Archives</h2>

            <form wire:submit="search_archives" class="flex flex-col justify-between gap-6 px-6 py-4 bg-gray-200 border-b-2 border-b-gray-400 dark:border-b-gray-200 md:gap-8 dark:bg-gray-800">
                <x-forms.input-text class="md:mx-6 lg:mx-12" for="search_query" placeholder_text="Type a name of an item..." title_text="Search archives" />

                <label class="py-2 text-2xl text-gray-800 dark:text-gray-200">Advanced Filters</label>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="grid items-center grid-cols-2">
                        <label class="text-gray-600 dark:text-gray-200 me-2">Brand</label>

                        <input wire:model="archive_brands_choice" class="col-span-3 sm:col-span-2" list="archive-brands" placeholder="Select brand" />
                        <datalist id="archive-brands">
                            <option value="SHOW ALL"></option>

                            @foreach ($brands as $brand)
                                <option value="{{ $brand }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="grid items-center grid-cols-2">
                        <label class="text-gray-600 dark:text-gray-200 me-2">Type</label>

                        <input wire:model="archive_types_choice" class="col-span-3 sm:col-span-2" list="archive-types" placeholder="Select type" />
                        <datalist id="archive-types">
                            <option value="SHOW ALL"></option>

                            @foreach ($types as $type)
                                <option value="{{ $type }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="grid items-center grid-cols-2">
                        <label class="text-gray-600 dark:text-gray-200 me-2">Month</label>

                        <select wire:model="archive_months_choice" class="col-span-3 sm:col-span-2">
                            <option value="">SHOW ALL</option>

                            @foreach ($months as $key => $month)
                                <option value="{{ $key }}">{{ ucfirst($month) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid items-center grid-cols-2">
                        <label class="text-gray-600 dark:text-gray-200 me-2">Year</label>

                        <select wire:model="archive_years_choice" class="col-span-3 sm:col-span-2">
                            <option value="">SHOW ALL</option>

                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid items-center grid-cols-2">
                        <label class="text-gray-600 dark:text-gray-200 me-2">Payment Method</label>

                        <select wire:model="archive_pay_methods_choice" class="col-span-3 sm:col-span-2">
                            <option value="">SHOW ALL</option>

                            @foreach (\App\Enums\PaymentMethods::cases() as $pay_method)
                                <option value="{{ $pay_method->value }}">{{ $pay_method->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid items-center grid-cols-2">
                        <label class="text-gray-600 dark:text-gray-200 me-2">Sell Method</label>

                        <select wire:model="archive_sell_methods_choice" class="col-span-3 sm:col-span-2">
                            <option value="">SHOW ALL</option>

                            @foreach (\App\Enums\SellMethods::cases() as $sell_method)
                                <option value="{{ $sell_method->value }}">{{ $sell_method->value }}</option>
                            @endforeach
                        </select>
                    </div>

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
                        class="grid grid-cols-1 sm:col-span-2 lg:col-span-3">
                        <label class="text-gray-600 dark:text-gray-200 me-2">Filter by tags</label>
                        <span x-on:click="clearTags"
                            class="flex gap-2 mt-2 text-sm text-blue-500 dark:text-blue-300 hover:cursor-pointer hover:underline"
                            title="Clear selected tags">
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
                    <input class="px-4 py-2 text-gray-800 transition duration-300 bg-red-300 cursor-pointer dark:bg-red-600 dark:text-gray-200 hover:bg-red-500 dark:hover:bg-red-700 hover:text-gray-100 dark:hover:text-gray-200" type="reset" value="Clear Form" />

                    <button type="submit" class="px-4 py-2 text-gray-800 transition duration-300 bg-green-300 cursor-pointer dark:bg-green-600 dark:text-gray-200 hover:bg-green-500 dark:hover:bg-green-700 hover:text-gray-100 dark:hover:text-gray-200">
                        <span wire:loading.flex wire:target="search_archives">
                            <x-loading-indicator
                                :loader_color_bg="'fill-gray-200'"
                                :loader_color_spin="'fill-gray-200'"
                                :showText="false"
                                :size="4"
                            />

                            <span class="ml-2">Searching</span>
                        </span>

                        <span wire:loading.remove wire:target="search_archives">Search</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="my-6">
            <h2 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-gray-200">Sold Items</h2>

            @if ($is_filtered && $sold_items->total() >= 1)
                <p class="px-2 text-green-800 dark:text-green-200">Your search returned {{ $sold_items->total() }} {{ $sold_items->total() == 1 ? 'result' : 'results' }}.</p>
            @endif

            {{ $sold_items->withQueryString()->links(data: ['scrollTo' => false]) }}

            <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-10 md:grid-cols-2 xl:grid-cols-3 xl:gap-x-8">
                @forelse ($sold_items as $sold_item)
                    <div class="relative">
                        <img src="{{ $sold_item->image_location ? asset('/storage/' .$sold_item->image_location) : 'https://tailwindui.com/plus/img/ecommerce-images/product-page-01-related-product-01.jpg' }}" class="object-cover w-full transition duration-300 bg-gray-200 rounded-md aspect-square group-hover:opacity-75 lg:aspect-auto lg:h-80 hover:scale-105" alt="Sold item image" title="Sold item image" loading="lazy" />

                        <div class="flex flex-col mt-4">
                            <div class="flex justify-between gap-2">
                                <h3 class="text-lg text-gray-800 grow shrink basis-0 dark:text-gray-200 md:truncate" title="{{ $sold_item->item_name }}">{{ $sold_item->item_name }}</h3>
                                <p class="text-lg text-gray-800 justify-self-end dark:text-gray-200" title="Price">&#8369; {{ $sold_item->price }}</p>
                            </div>

                            <div class="flex justify-between gap-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" title="Date sold">{{ Carbon\Carbon::parse($sold_item->date_sold)->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500 justify-self-end dark:text-gray-400" title="Size">{{ $sold_item->size }}</p>
                            </div>

                            <div class="flex justify-between gap-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" title="Condition">{{ $sold_item->condition }}</p>
                                <p class="text-sm justify-self-end text-gray-500 max-w-full dark:text-gray-400 md:truncate md:max-w-[200px] {{ !$sold_item->tags ? 'italic' : '' }}" title="{{ $sold_item->tags }}">{{ $sold_item->tags ? $sold_item->tags : 'No tags' }}</p>
                            </div>

                            <div class="flex justify-between gap-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" title="Pay method">{{ $sold_item->pay_method->method }}</p>
                                <p class="text-sm justify-self-end text-gray-500 max-w-full dark:text-gray-400 md:truncate md:max-w-[200px]" title="Remittance location">{{ $sold_item->pay_method->remittance_location }}</p>
                            </div>

                            <div class="flex justify-between gap-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" title="Sell method">{{ $sold_item->sell_method->method }}</p>
                                <p class="text-sm justify-self-end text-gray-500 max-w-full dark:text-gray-400 md:truncate md:max-w-[200px]" title="Sell location">{{ $sold_item->sell_method->location }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-1 px-2 text-red-800 dark:text-red-200 md:col-span-2 xl:col-span-3">No sold items found for this filter. Please adjust your filters.</p>
                @endforelse
            </div>

            {{ $sold_items->withQueryString()->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
