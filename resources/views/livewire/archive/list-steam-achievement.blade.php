<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Steam Achievements</x-slot>

    <div>
        <p class="text-xl text-gray-500 dark:text-gray-300">A list of games I have 100% completed on Steam</p>

        <div class="my-6 overflow-x-auto">
            <x-clipped-table
                corners="tl tr bl br"
                :rows="$steam_achievements"
                :columns="[
                    'number' => '#',
                    'game_name' => 'Game',
                    'date_completed' => 'Date Completed',
                    'tags' => 'Tags',
                    'notes' => 'Notes',
                ]"
                is-sortable
                :sortable-columns="['game_name', 'date_completed', 'tags']"
                striped
                class="min-w-full text-left bg-gray-100 dark:bg-gray-900"
            >
                <template x-for="(row, index) in sortedRows" :key="row.id">
                    <tr class="text-gray-800 dark:text-gray-200">
                        <td class="px-4 py-3 font-text border-b border-gray-300 dark:border-gray-600" x-text="String(index + 1).padStart(2, '0')"></td>
                        <td class="px-4 py-3 font-text border-b border-gray-300 dark:border-gray-600 min-w-56" x-text="row.game_name"></td>
                        <td class="px-4 py-3 font-text border-b border-gray-300 dark:border-gray-600 min-w-44" x-text="row.date_completed_display"></td>
                        <td class="px-4 py-3 font-text border-b border-gray-300 dark:border-gray-600">
                            <template x-if="row.tags">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(tag, tagIndex) in row.tags_array" :key="tagIndex">
                                        {{-- TODO: ADD A NEW COMPONENT FOR ROUND BADGES --}}
                                        <span class="text-gray-800 dark:text-gray-200 border-gray-800 dark:border-gray-200 px-2 py-1 border-2 rounded-xl text-sm font-subtext" x-text="tag"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="! row.tags">
                                <span class="italic font-text text-gray-600 dark:text-gray-400">No tags</span>
                            </template>
                        </td>
                        <td
                            class="px-4 py-3 font-text border-b border-gray-300 dark:border-gray-600"
                            x-bind:class="! row.notes ? 'italic text-gray-600 dark:text-gray-400' : ''"
                            x-text="row.notes || 'No notes'"
                        ></td>
                    </tr>
                </template>

                <template x-if="sortedRows.length === 0">
                    <tr>
                        <td colspan="5" class="px-4 py-3 font-subtext text-sm tracking-widest uppercase text-gray-900 dark:text-white">There are no steam achievements.</td>
                    </tr>
                </template>
            </x-clipped-table>
        </div>
    </div>
</div>
