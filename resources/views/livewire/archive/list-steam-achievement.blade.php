<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Steam Achievements</x-slot>

    <div>
        <p class="text-xl text-gray-500 dark:text-gray-300">A list of games I have 100% completed on Steam</p>

        <div class="my-6 overflow-x-auto">
            <table class="min-w-full text-left border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-200 dark:bg-gray-800">
                    <tr class="text-gray-800 dark:text-gray-200">
                        <th class="px-4 py-3 font-semibold border-b border-gray-300 dark:border-gray-600">#</th>
                        <th class="px-4 py-3 font-semibold border-b border-gray-300 dark:border-gray-600">Game</th>
                        <th class="px-4 py-3 font-semibold border-b border-gray-300 dark:border-gray-600">Date Completed</th>
                        <th class="px-4 py-3 font-semibold border-b border-gray-300 dark:border-gray-600">Tags</th>
                        <th class="px-4 py-3 font-semibold border-b border-gray-300 dark:border-gray-600">Notes</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($steam_achievements as $steam_achievement)
                        <tr class="text-gray-700 odd:bg-gray-50 even:bg-gray-100 dark:text-gray-300 dark:odd:bg-gray-900 dark:even:bg-gray-800">
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">{{ $steam_achievement->game_name }}</td>
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">{{ \Carbon\Carbon::parse($steam_achievement->date_completed)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">
                                @if ($steam_achievement->tags)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach (explode(',', $steam_achievement->tags) as $tag)
                                            <span class="px-3 py-1 m-0.5 text-sm text-gray-800 bg-gray-200 rounded-full whitespace-nowrap dark:bg-gray-700 dark:text-gray-200">{{ trim($tag) }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="italic text-gray-500 dark:text-gray-400">No tags</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 {{ !$steam_achievement->notes ? 'italic text-gray-500 dark:text-gray-400' : '' }}">{{ $steam_achievement->notes ?: 'No notes' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-red-800 dark:text-red-200">There are no steam achievements.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
