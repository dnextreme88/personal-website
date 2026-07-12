<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <x-slot name="header">Steam Achievements</x-slot>

    <div>
        <p class="text-xl text-gray-500 dark:text-gray-300">A list of games I have 100% completed on Steam</p>

        <div
            x-data="{
                rows: @js($steam_achievements),
                sortColumn: null,
                sortDirection: 'asc',
                sort(column) {
                    if (this.sortColumn === column) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortColumn = column;
                        this.sortDirection = 'asc';
                    }
                },
                indicator(column) {
                    if (this.sortColumn !== column) return '↕';

                    return this.sortDirection === 'asc' ? '↑' : '↓';
                },
                get sortedRows() {
                    if (! this.sortColumn) return this.rows;

                    const column = this.sortColumn;
                    const direction = this.sortDirection === 'asc' ? 1 : -1;

                    return [...this.rows].sort((a, b) => {
                        if (column === 'tags') {
                            const aHasTags = a.tags_array.length > 0;
                            const bHasTags = b.tags_array.length > 0;

                            // Group by tag presence first: ascending keeps tagged games on top,
                            // descending pushes untagged games on top.
                            if (aHasTags !== bHasTags) {
                                return aHasTags ? -direction : direction;
                            }

                            // Both untagged: nothing more to compare.
                            if (!aHasTags) return 0;

                            // Both tagged: order by the starting letter of the first tag.
                            return a.tags_array[0].localeCompare(b.tags_array[0], undefined, { sensitivity: 'base' }) * direction;
                        }

                        if (column === 'date_completed') {
                            return a.date_completed.localeCompare(b.date_completed) * direction;
                        }

                        return a.game_name.localeCompare(b.game_name, undefined, { sensitivity: 'base' }) * direction;
                    });
                }
            }"
            class="my-6 overflow-x-auto"
        >
            <table class="min-w-full text-left border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-200 dark:bg-gray-800">
                    <tr class="text-gray-800 dark:text-gray-200">
                        <th class="px-4 py-3 font-semibold border-b border-gray-300 dark:border-gray-600">#</th>
                        <th
                            x-on:click="sort('game_name')"
                            class="px-4 py-3 font-semibold transition-colors border-b border-gray-300 cursor-pointer select-none dark:border-gray-600 hover:text-blue-700 dark:hover:text-blue-300 min-w-48"
                            title="Sort by game"
                        >
                            Game <span class="text-gray-500 dark:text-gray-400" x-text="indicator('game_name')"></span>
                        </th>
                        <th
                            x-on:click="sort('date_completed')"
                            class="px-4 py-3 font-semibold transition-colors border-b border-gray-300 cursor-pointer select-none dark:border-gray-600 hover:text-blue-700 dark:hover:text-blue-300 min-w-48"
                            title="Sort by date completed"
                        >
                            Date Completed <span class="text-gray-500 dark:text-gray-400" x-text="indicator('date_completed')"></span>
                        </th>
                        <th
                            x-on:click="sort('tags')"
                            class="px-4 py-3 font-semibold transition-colors border-b border-gray-300 cursor-pointer select-none dark:border-gray-600 hover:text-blue-700 dark:hover:text-blue-300"
                            title="Sort by tags (ascending: tagged first, descending: untagged first)"
                        >
                            Tags <span class="text-gray-500 dark:text-gray-400" x-text="indicator('tags')"></span>
                        </th>
                        <th class="px-4 py-3 font-semibold border-b border-gray-300 dark:border-gray-600">Notes</th>
                    </tr>
                </thead>

                <tbody>
                    <template x-for="(row, index) in sortedRows" :key="row.id">
                        <tr class="text-gray-700 odd:bg-gray-50 even:bg-gray-100 dark:text-gray-300 dark:odd:bg-gray-900 dark:even:bg-gray-800">
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600" x-text="index + 1"></td>
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600" x-text="row.game_name"></td>
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600" x-text="row.date_completed_display"></td>
                            <td class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">
                                <template x-if="row.tags">
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(tag, tagIndex) in row.tags_array" :key="tagIndex">
                                            <span class="px-3 py-1 m-0.5 text-sm text-gray-800 bg-gray-200 rounded-full whitespace-nowrap dark:bg-gray-700 dark:text-gray-200" x-text="tag"></span>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="! row.tags">
                                    <span class="italic text-gray-500 dark:text-gray-400">No tags</span>
                                </template>
                            </td>
                            <td
                                class="px-4 py-3 border-b border-gray-300 dark:border-gray-600"
                                x-bind:class="! row.notes ? 'italic text-gray-500 dark:text-gray-400' : ''"
                                x-text="row.notes || 'No notes'"
                            ></td>
                        </tr>
                    </template>

                    <template x-if="sortedRows.length === 0">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-red-800 dark:text-red-200">There are no steam achievements.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
