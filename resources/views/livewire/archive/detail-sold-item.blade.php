<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">{{ $sold_item->item_name }}</x-slot>

    <div class="mt-4">
        {{-- Item image. The neon border lives on the wrapper: card-square paints it
            through a ::before, which an <img> cannot host. --}}
        <div class="relative rounded-md card-square">
            <img
                src="{{ $sold_item->image_location ? asset('/storage/' .$sold_item->image_location) : asset('/images/no-image-available-placeholder-1920x1080-transparent.svg') }}"
                class="object-cover bg-transparent rounded-md aspect-square w-full"
                @php
                    $image_text = $sold_item->image_location ? 'Image of ' .$sold_item->item_name : 'No image found for ' .$sold_item->item_name;
                @endphp
                alt="{{ $image_text }}"
                title="{{ $image_text }}"
            />
        </div>

        {{-- Item details: one element per line --}}
        <div class="flex flex-col gap-3 mt-6">
            <h3 class="text-2xl text-gray-800 dark:text-gray-200 font-heading" title="{{ $sold_item->item_name }}">{{ $sold_item->item_name }}@if (str_contains(strtolower($sold_item->tags ?? ''), 'hot item')) <span class="text-xl leading-none" title="Has more than 10 inquiries!">&#128293;</span>@endif</h3>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">Price</p>
                <p class="text-lg text-gray-800 dark:text-gray-200">&#8369; {{ $sold_item->price }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">Date sold</p>
                <p class="text-lg text-gray-800 dark:text-gray-200">{{ Carbon\Carbon::parse($sold_item->date_sold)->format('M d, Y') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">Size</p>
                <p class="text-lg text-gray-800 dark:text-gray-200">{{ $sold_item->size }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">Condition</p>
                <p class="text-lg text-gray-800 dark:text-gray-200">{{ $sold_item->condition }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">Tags</p>
                <p class="text-lg text-gray-800 dark:text-gray-200 {{ !$sold_item->tags ? 'italic' : '' }}">{{ $sold_item->tags ? $sold_item->tags : 'No tags' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">Pay method</p>
                <p class="text-lg text-gray-800 dark:text-gray-200">{{ $sold_item->pay_method->method }} &mdash; {{ $sold_item->pay_method->remittance_location }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">Sell method</p>
                <p class="text-lg text-gray-800 dark:text-gray-200">{{ $sold_item->sell_method->method }} &mdash; {{ $sold_item->sell_method->location }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">Description</p>

                @if ($sold_item->description)
                    <div class="text-lg text-gray-800 dark:text-gray-200 prose prose-xl lg:prose-lg dark:prose-invert max-w-none prose-sold-items-desc">{!! Markdown::parse($sold_item->description) !!}</div>
                @else
                    <p class="text-lg italic text-gray-800 dark:text-gray-200">No description</p>
                @endif
            </div>
        </div>

        <div class="mt-8">
            <a wire:navigate class="text-cyan-800 dark:text-cyan-200 hover:text-cyan-600 dark:hover:text-cyan-400" href="{{ route('archive.sold-items.list') }}">&larr; Back to Sold Items</a>
        </div>
    </div>
</div>
