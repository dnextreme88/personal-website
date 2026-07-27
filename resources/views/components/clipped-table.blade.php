@props([
    'corners' => [],
    'size' => '16px',
    // Optional zebra striping for the tbody rows. `striped` toggles it on;
    // `stripeColor` picks the alternating-row colour (any CSS colour — a
    // translucent grey reads as neutral shading over both light and dark
    // surfaces without tinting the rows a colour).
    'striped' => false,
    'stripeColor' => 'rgba(128, 128, 128, 0.12)',
    // Optional managed header + client-side sorting. Pass `columns` as an
    // ordered ['key' => 'Label'] map to render a unified <thead>; the slot then
    // only supplies the tbody rows (wrapped in <tbody> here). `rows` seeds the
    // Alpine data the rows iterate as `sortedRows`. With `isSortable` on, every
    // column whose key is in `sortableColumns` becomes a clickable sort header.
    // When `columns` is empty the component renders the slot as-is (legacy mode).
    'columns' => [],
    'rows' => [],
    'isSortable' => false,
    'sortableColumns' => [],
])

@php
    // Accept corners as an array (['tl', 'br']) or a delimited string ("tl br" / "tl,br").
    $active = is_array($corners)
        ? $corners
        : preg_split('/[\s,]+/', trim((string) $corners), -1, PREG_SPLIT_NO_EMPTY);
    $active = array_map('strtolower', (array) $active);

    $n = $size;

    $tl = in_array('tl', $active, true);
    $tr = in_array('tr', $active, true);
    $bl = in_array('bl', $active, true);
    $br = in_array('br', $active, true);

    // Build the clip-path polygon clockwise from the top-left. Each requested
    // corner becomes a diagonal bevel (two points); the rest stay square.
    // Same notch logic as the "Contact Me" CTA button (.clip-notch = tl + br).
    $points = [];

    $points[] = $tl ? "{$n} 0" : '0 0';

    if ($tr) {
        $points[] = "calc(100% - {$n}) 0";
        $points[] = "100% {$n}";
    } else {
        $points[] = '100% 0';
    }

    if ($br) {
        $points[] = "100% calc(100% - {$n})";
        $points[] = "calc(100% - {$n}) 100%";
    } else {
        $points[] = '100% 100%';
    }

    if ($bl) {
        $points[] = "{$n} 100%";
        $points[] = "0 calc(100% - {$n})";
    } else {
        $points[] = '0 100%';
    }

    if ($tl) {
        $points[] = "0 {$n}";
    }

    $clip_path = 'polygon(' . implode(', ', $points) . ')';

    // Preserve any inline style the caller passed alongside the table clip-path.
    $caller_style = $attributes->get('style');
    $table_style = 'clip-path: ' . $clip_path . ';';

    // Opt-in zebra striping: flag the table and expose the colour as a CSS var
    // the stylesheet reads for even rows (see .clipped-table--striped in app.css).
    $table_class = 'clipped-table w-full border-collapse';

    if ($striped) {
        $table_class .= ' clipped-table--striped';
        $table_style .= ' --clipped-table-stripe: ' . $stripeColor . ';';
    }

    $table_style .= $caller_style ? ' ' . $caller_style : '';

    // Managed-header mode is on whenever a columns map is supplied.
    $managed = ! empty($columns);
    $sortableColumns = (array) $sortableColumns;

    // Shared unified header cell styling so every clipped-table looks the same.
    $headerBase = 'px-4 py-3 font-subtext text-xs font-semibold tracking-widest uppercase border-b-2 text-cyan-800 dark:text-neon-cyan border-cyan-700/50 dark:border-neon-cyan/60';
    $headerSortable = 'transition-colors cursor-pointer select-none hover:text-cyan-600 dark:hover:text-cyan-500';
@endphp

{{-- Neon gradient border that follows the bevelled clip path. Two stacked
     clipped layers (REF: https://stackoverflow.com/a/70238234): the frame is a
     cyan→magenta gradient clipped to the outer shape; the table sits 2px inside
     (the frame's padding), re-clipped to the same shape — so the gradient shows
     as a border tracing every edge, straight sides and diagonal bevels alike. --}}
<div
    class="clipped-table-frame inline-block min-w-full p-0.5 align-top"
    style="clip-path: {{ $clip_path }}; background: linear-gradient(135deg, var(--color-neon-cyan), var(--color-neon-magenta));"
    @if ($managed)
        x-data="{
            rows: @js($rows),
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
                    const aValue = a[column] ?? '';
                    const bValue = b[column] ?? '';

                    return String(aValue).localeCompare(String(bValue), undefined, { numeric: true, sensitivity: 'base' }) * direction;
                });
            },
        }"
    @endif
>
    <table {{ $attributes->except('style')->merge(['class' => $table_class]) }} style="{{ $table_style }}">
        @if ($managed)
            <thead class="bg-gray-100 dark:bg-gray-900">
                <tr>
                    @foreach ($columns as $key => $label)
                        @php $sortable = $isSortable && in_array($key, $sortableColumns, true); @endphp

                        <th
                            @class([$headerBase, $headerSortable => $sortable])
                            @if ($sortable)
                                x-on:click="sort('{{ $key }}')"
                                title="Sort by {{ strtolower($label) }}"
                            @endif
                        >
                            {{ $label }}
                            @if ($sortable)
                                <span
                                    x-bind:class="sortColumn === '{{ $key }}' ? 'text-neon-magenta' : 'text-cyan-600 dark:text-cyan-600'"
                                    x-text="indicator('{{ $key }}')"
                                ></span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>{{ $slot }}</tbody>
        @else
            {{ $slot }}
        @endif
    </table>
</div>
