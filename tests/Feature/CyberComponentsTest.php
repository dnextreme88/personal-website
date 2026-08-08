<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;

it('renders the cyber button as a navigating anchor with notch and neon border', function () {
    $html = Blade::render('<x-button-call-to-action href="/contact">Contact</x-button-call-to-action>');

    expect($html)
        ->toContain('btn-call-to-action')
        ->toContain('clip-notch')
        // The neon border is a cyan→magenta gradient (a plain border would be
        // cut off by the diagonal notch), not the `.neon-border` utility.
        ->toContain('from-neon-cyan')
        ->toContain('to-neon-magenta')
        ->toContain('wire:navigate')
        ->toContain('href="/contact"')
        ->toContain('Contact');
});

it('renders the cyber button as a button element when no href is given', function () {
    $html = Blade::render('<x-button-call-to-action>Go</x-button-call-to-action>');

    expect($html)
        ->toContain('<button')
        ->toContain('type="button"')
        ->not->toContain('wire:navigate');
});

it('uses the magenta sweep for the magenta variant', function () {
    $html = Blade::render('<x-button-call-to-action variant="magenta">Go</x-button-call-to-action>');

    expect($html)->toContain('via-neon-magenta/30');
});

it('injects a surface class onto the button face', function () {
    $html = Blade::render('<x-button-call-to-action surface_class="bg-gradient-blue">Go</x-button-call-to-action>');

    // Must land on the fill span (after its bg classes), not fall through as a
    // stray `surface_class` attribute on the root — the snake_case prop only
    // binds a snake_case attribute.
    expect($html)
        ->toContain('dark:bg-gray-200 bg-gradient-blue')
        ->not->toContain('surface_class=');
});

it('renders four hud corner brackets', function () {
    $html = Blade::render('<x-hud-corners />');

    expect(substr_count($html, 'hud-corner '))->toBe(4)
        ->and($html)->toContain('hud-corner-tl')
        ->toContain('hud-corner-tr')
        ->toContain('hud-corner-bl')
        ->toContain('hud-corner-br');
});

it('renders the magenta hud corner variant', function () {
    $html = Blade::render('<x-hud-corners variant="magenta" />');

    expect($html)->toContain('hud-corner-magenta');
});

it('renders the scanline overlay fixed and non-interactive', function () {
    $html = Blade::render('<x-scanline-overlay />');

    expect($html)
        ->toContain('scanline-overlay')
        ->toContain('fixed')
        ->toContain('pointer-events-none');
});

it('renders the cyber loader with terminal boot text and a blinking cursor', function () {
    $html = Blade::render('<x-loading-indicator text="BOOTING" />');

    expect($html)
        ->toContain('BOOTING')
        ->toContain('blinking-cursor')
        ->toContain('font-loader');
});

it('clips only the requested corners of the table', function () {
    $html = Blade::render('<x-clipped-table corners="tl br"><tbody></tbody></x-clipped-table>');

    expect($html)
        ->toContain('<table')
        ->toContain('clipped-table-frame')
        ->toContain('clipped-table')
        ->toContain('clip-path: polygon(')
        // top-left bevel (opens at "16px 0", closes at "0 16px")
        ->toContain('16px 0')
        ->toContain('0 16px')
        // bottom-right bevel
        ->toContain('100% calc(100% - 16px)')
        ->toContain('calc(100% - 16px) 100%')
        // top-right and bottom-left stay square
        ->not->toContain('100% 16px')
        ->not->toContain('16px 100%');
});

it('wraps the table in a cyan-to-magenta gradient border frame', function () {
    $html = Blade::render('<x-clipped-table corners="tl br"><tbody></tbody></x-clipped-table>');

    expect($html)
        ->toContain('clipped-table-frame')
        ->toContain('linear-gradient(135deg, var(--color-neon-cyan), var(--color-neon-magenta))');
});

it('leaves the table a plain rectangle when no corners are given', function () {
    $html = Blade::render('<x-clipped-table><tbody></tbody></x-clipped-table>');

    expect($html)->toContain('clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%)');
});

it('bevels all four corners and honours a custom size', function () {
    $html = Blade::render('<x-clipped-table :corners="[\'tl\', \'tr\', \'bl\', \'br\']" size="10px"><tbody></tbody></x-clipped-table>');

    expect($html)
        ->toContain('clip-path: polygon(10px 0, calc(100% - 10px) 0, 100% 10px, 100% calc(100% - 10px), calc(100% - 10px) 100%, 10px 100%, 0 calc(100% - 10px), 0 10px)');
});

it('preserves caller classes and inline styles on the table', function () {
    $html = Blade::render('<x-clipped-table corners="tr" class="min-w-full" style="color: red"><tbody></tbody></x-clipped-table>');

    expect($html)
        ->toContain('min-w-full')
        ->toContain('clipped-table')
        ->toContain('color: red');
});

it('is not striped by default', function () {
    $html = Blade::render('<x-clipped-table><tbody></tbody></x-clipped-table>');

    expect($html)
        ->not->toContain('clipped-table--striped')
        ->not->toContain('--clipped-table-stripe');
});

it('flags the table for striping when the striped prop is set', function () {
    $html = Blade::render('<x-clipped-table striped><tbody></tbody></x-clipped-table>');

    expect($html)
        ->toContain('clipped-table--striped')
        // Falls back to the neutral default stripe colour when none is given.
        ->toContain('--clipped-table-stripe: rgba(128, 128, 128, 0.12)');
});

it('uses the given stripe color for alternating rows', function () {
    $html = Blade::render('<x-clipped-table striped stripe-color="rgba(1, 2, 3, 0.5)"><tbody></tbody></x-clipped-table>');

    expect($html)
        ->toContain('clipped-table--striped')
        ->toContain('--clipped-table-stripe: rgba(1, 2, 3, 0.5)');
});

it('renders a managed thead from the columns map and wraps the slot in a tbody', function () {
    $html = Blade::render('<x-clipped-table :columns="[\'game_name\' => \'Game\', \'notes\' => \'Notes\']"><tr></tr></x-clipped-table>');

    expect($html)
        ->toContain('<thead')
        ->toContain('Game')
        ->toContain('Notes')
        ->toContain('<tbody>');
});

it('makes only whitelisted columns sortable when is-sortable is on', function () {
    $html = Blade::render('<x-clipped-table is-sortable :columns="[\'game_name\' => \'Game\', \'notes\' => \'Notes\']" :sortable-columns="[\'game_name\']"><tr></tr></x-clipped-table>');

    expect($html)
        ->toContain("sort('game_name')")
        ->toContain("indicator('game_name')")
        // "notes" is not whitelisted, so it gets no click handler or indicator.
        ->not->toContain("sort('notes')")
        ->not->toContain("indicator('notes')");
});

it('renders headers without sort handlers when is-sortable is off', function () {
    $html = Blade::render('<x-clipped-table :columns="[\'game_name\' => \'Game\']" :sortable-columns="[\'game_name\']"><tr></tr></x-clipped-table>');

    expect($html)
        ->toContain('<thead')
        ->not->toContain('x-on:click')
        ->not->toContain("indicator('game_name')");
});

it('seeds the alpine sort state with the given rows', function () {
    $html = Blade::render('<x-clipped-table :columns="[\'game_name\' => \'Game\']" :rows="[[\'game_name\' => \'Desolate\']]"><tr></tr></x-clipped-table>');

    expect($html)
        ->toContain('x-data')
        ->toContain('sortedRows')
        ->toContain('Desolate');
});

it('renders the slot directly with no managed header in legacy mode', function () {
    $html = Blade::render('<x-clipped-table><tbody></tbody></x-clipped-table>');

    expect($html)
        ->not->toContain('<thead')
        ->not->toContain('x-data');
});

it('renders the submit button as a button element that submits by default', function () {
    $html = Blade::render('<x-forms.button-submit>Send</x-forms.button-submit>');

    expect($html)
        ->toContain('btn-submit')
        ->toContain('<button')
        ->toContain('type="submit"')
        ->toContain('Send')
        ->not->toContain('wire:navigate');
});

it('honours a caller-supplied type without duplicating the attribute', function () {
    $html = Blade::render('<x-forms.button-submit type="reset">Reset</x-forms.button-submit>');

    expect($html)
        ->toContain('type="reset"')
        ->not->toContain('type="submit"')
        // The type attribute is emitted exactly once (not echoed again by the merge).
        ->and(substr_count($html, 'type='))->toBe(1);
});

it('uses a solid green border rather than the cyan-to-magenta gradient', function () {
    $html = Blade::render('<x-forms.button-submit>Send</x-forms.button-submit>');

    expect($html)
        ->toContain('bg-green-500');
});

it('uses the font-loader font class on the submit button label', function () {
    $html = Blade::render('<x-forms.button-submit>Send</x-forms.button-submit>');

    // Buttons use font-loader per the project font conventions (CLAUDE.md).
    expect($html)->toContain('font-loader');
});

it('fills the submit button with green and keeps the label readable in both modes', function () {
    $html = Blade::render('<x-forms.button-submit>Send</x-forms.button-submit>');

    expect($html)
        // Green fill: dark green in light mode, light green in dark mode.
        ->toContain('bg-green-800 dark:bg-green-200')
        // Label stays white on the dark fill, flips to dark on the light fill.
        ->toContain('text-white dark:text-gray-900');
});

it('leaves the submit button a plain rectangle when no bevels are given', function () {
    $html = Blade::render('<x-forms.button-submit>Send</x-forms.button-submit>');

    expect($html)->toContain('clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%)');
});

it('bevels only the requested corners of the submit button', function () {
    $html = Blade::render('<x-forms.button-submit bevels="tl br">Send</x-forms.button-submit>');

    expect($html)
        ->toContain('clip-path: polygon(')
        // top-left bevel (opens at "10px 0", closes at "0 10px")
        ->toContain('10px 0')
        ->toContain('0 10px')
        // bottom-right bevel
        ->toContain('100% calc(100% - 10px)')
        ->toContain('calc(100% - 10px) 100%')
        // top-right and bottom-left stay square
        ->not->toContain('100% 10px')
        ->not->toContain('10px 100%');
});

it('honours a custom bevel size on the submit button', function () {
    $html = Blade::render('<x-forms.button-submit bevels="tl" size="20px">Send</x-forms.button-submit>');

    expect($html)
        ->toContain('20px 0')
        ->toContain('0 20px');
});

it('injects a surface class onto the submit button fill', function () {
    $html = Blade::render('<x-forms.button-submit surface-class="bg-gradient-blue">Send</x-forms.button-submit>');

    expect($html)->toContain('bg-gradient-blue');
});

it('renders the next/previous button as a button element by default with the neon card border', function () {
    $html = Blade::render('<x-button-next-previous text="Previous" />');

    expect($html)
        ->toContain('<button')
        ->toContain('type="button"')
        ->toContain('card-rectangle')
        ->toContain('Previous');
});

it('renders the next/previous button as a navigating anchor and passes href through', function () {
    $html = Blade::render('<x-button-next-previous as="a" wire:navigate href="/blog/1-foo" text="&larr; Previous" />');

    expect($html)
        ->toContain('<a')
        ->toContain('wire:navigate')
        ->toContain('href="/blog/1-foo"')
        ->toContain('card-rectangle');
});

it('renders the disabled next/previous state as a muted borderless span', function () {
    $html = Blade::render('<x-button-next-previous as="span" is_disabled="true" text="Previous" />');

    expect($html)
        ->toContain('<span')
        ->toContain('cursor-not-allowed')
        // The disabled state must NOT wear the neon gradient border (it implies clickable).
        ->not->toContain('card-rectangle');
});

it('mutes a disabled button independently of its tag via is_disabled', function () {
    $html = Blade::render('<x-button-next-previous as="button" is_disabled="true" text="&lt;" />');

    expect($html)
        ->toContain('<button')
        ->toContain('cursor-not-allowed')
        ->not->toContain('card-rectangle');
});

it('keeps the neon card border when is_disabled is absent or falsey', function () {
    // is_disabled drives the muted look, not the tag: a span without it stays enabled,
    // and is_disabled="false" (string) must not be treated as truthy.
    $span = Blade::render('<x-button-next-previous as="span" text="Previous" />');
    $falsey = Blade::render('<x-button-next-previous as="button" is_disabled="false" text="Next" />');

    expect($span)
        ->toContain('card-rectangle')
        ->not->toContain('cursor-not-allowed');
    expect($falsey)
        ->toContain('card-rectangle')
        ->not->toContain('cursor-not-allowed');
});

it('hides the subtext unless show_subtext is set', function () {
    $hidden = Blade::render('<x-button-next-previous as="a" href="/x" text="&larr; Previous" subtext="My Post Title" />');
    $shown = Blade::render('<x-button-next-previous as="a" href="/x" text="&larr; Previous" subtext="My Post Title" show_subtext />');

    expect($hidden)->not->toContain('My Post Title');
    expect($shown)->toContain('My Post Title');
});

it('escapes the subtext but renders the text raw', function () {
    $html = Blade::render('<x-button-next-previous as="a" href="/x" text="&laquo; Previous" subtext="<b>x</b>" show_subtext />');

    expect($html)
        // text keeps its HTML entity (translation strings ship entities like &laquo;).
        ->toContain('&laquo; Previous')
        // subtext is content, so any markup in it must be escaped, not rendered.
        ->toContain('&lt;b&gt;x&lt;/b&gt;')
        ->not->toContain('<b>x</b>');
});

it('labels the interactive root with the accessible name, decoding entities', function () {
    $html = Blade::render('<x-button-next-previous as="a" href="/x" text="&larr; Previous" />');

    expect($html)
        // The root anchor carries the accessible name with entities decoded to real characters.
        ->toContain('aria-label="← Previous"')
        // The old malformed disabled placeholder must be gone.
        ->not->toContain('aria-label="true"');
});

it('folds the subtext into the accessible name when one is provided', function () {
    $html = Blade::render('<x-button-next-previous as="a" href="/x" text="&larr; Previous" subtext="Poem #1" show_subtext />');

    expect($html)->toContain('aria-label="← Previous: Poem #1"');
});

it('passes livewire, alpine, dusk, and extra class attributes through to the root', function () {
    $html = Blade::render('<x-button-next-previous as="button" text="Next" class="ml-3" wire:click="nextPage" x-on:click="scroll()" dusk="nextPage.before" />');

    expect($html)
        ->toContain('ml-3')
        ->toContain('wire:click="nextPage"')
        ->toContain('x-on:click="scroll()"')
        ->toContain('dusk="nextPage.before"');
});

it('throws when the required text prop is missing', function () {
    // Blade wraps the InvalidArgumentException from the component in a ViewException,
    // but the guard message is preserved.
    Blade::render('<x-button-next-previous as="button" />');
})->throws(ViewException::class, 'The [text] prop is required for <x-button-next-previous>.');

it('renders the badge as a neutral span pill by default', function () {
    $html = Blade::render('<x-badge>hot item</x-badge>');

    expect($html)
        ->toContain('<span')
        ->toContain('rounded-xl')
        ->toContain('font-subtext')
        // Neutral variant: readable bordered grey, no neon glow class.
        ->toContain('border-gray-800 dark:border-gray-200')
        ->not->toContain('badge-neon')
        ->toContain('hot item');
});

it('renders the neon cyan and magenta badge variants with their glow class', function () {
    $cyan = Blade::render('<x-badge variant="cyan">on</x-badge>');
    $magenta = Blade::render('<x-badge variant="magenta">off</x-badge>');

    expect($cyan)
        ->toContain('badge-neon-cyan')
        ->toContain('border-cyan-700 dark:border-cyan-300');
    expect($magenta)
        ->toContain('badge-neon-magenta')
        ->toContain('border-fuchsia-700 dark:border-fuchsia-300');
});

it('bakes no colours for the none variant so the caller can drive them', function () {
    $html = Blade::render('<x-badge variant="none">tag</x-badge>');

    expect($html)
        ->toContain('rounded-xl')
        // Structure only: no baked text/border colour utilities, no glow.
        ->not->toContain('badge-neon')
        ->not->toContain('border-gray-800')
        ->not->toContain('text-gray-800');
});

it('renders the badge with a custom tag and the interactive affordance', function () {
    $html = Blade::render('<x-badge as="li" interactive>tag</x-badge>');

    expect($html)
        ->toContain('<li')
        ->toContain('hover:cursor-pointer');
});

it('passes alpine and extra attributes through to the badge root', function () {
    $html = Blade::render('<x-badge as="li" variant="none" x-on:click="pick()" x-bind:class="cls" class="mt-2">tag</x-badge>');

    expect($html)
        ->toContain('x-on:click="pick()"')
        ->toContain('x-bind:class="cls"')
        ->toContain('mt-2');
});
