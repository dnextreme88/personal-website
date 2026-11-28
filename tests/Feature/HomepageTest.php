<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the identity hero with the portrait and a single h1', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('images/homepage-hero.webp', false)
        ->assertSee('Portrait of Jeanne Kevin T. Decena')
        ->assertSee('Web developer.');

    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

it('offers the hero portrait in three sizes through srcset', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('images/homepage-hero-480.webp 480w', false)
        ->assertSee('images/homepage-hero-768.webp 768w', false)
        ->assertSee('images/homepage-hero.webp 1152w', false)
        ->assertSee('sizes="(min-width: 64rem) and (max-aspect-ratio: 9/8)', false)
        ->assertSee('(min-width: 80rem)', false);

    foreach (['homepage-hero-480.webp', 'homepage-hero-768.webp', 'homepage-hero.webp'] as $file) {
        expect(file_exists(public_path('images/'.$file)))->toBeTrue();
    }
});

it('keeps the hero at full screen height below the header at every width', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    expect($content)
        ->toContain('class="hero-identity')
        ->toContain('id="site-header"')
        ->toContain('--site-header-h');

    $css = file_get_contents(resource_path('css/app.css'));

    // One full-height rule for every width, and no breakpoint that overrides it.
    expect(substr_count($css, 'min-height: calc(100svh - 2rem - var(--site-header-h, 84px));'))->toBe(1)
        ->and($css)->not->toContain('calc(var(--hero-portrait-fit) / 0.9)');
});

it('orbits a decorative ring of photo thumbnails around the portrait', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    expect(substr_count($content, 'class="portrait-ring__card"'))->toBe(18)
        ->and($content)->toContain('class="portrait-ring__tilt"')
        ->and($content)->toContain('animate-ring-orbit');

    // The ring is decoration: hidden from screen readers.
    expect($content)->toMatch('/aria-hidden="true" class="portrait-ring__tilt"/');

    // Nine unique photos, each shown twice around the 18-card ring.
    foreach (range(1, 9) as $n) {
        $path = 'images/portrait-ring/photo-'.$n.'.webp';

        expect(substr_count($content, $path))->toBe(2)
            ->and(file_exists(public_path($path)))->toBeTrue();

        // Thumbnails are cropped to the card's 3:2 shape at 480x320.
        [$width, $height] = getimagesize(public_path($path));

        expect($width)->toBe(480)
            ->and($height)->toBe(320);
    }
});

it('shows the about section with a contact button and the facts panel turned off', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['id="about"', 'Contact me', 'View the full resume'], false)
        // The facts panel is commented out in Blade, so it is not rendered.
        ->assertDontSee('Open to work');
});

it('fades the About heading in on scroll like the other section headings', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    $fade_in = 'glitch scroll-mt-(--anchor-offset) text-glow text-3xl tracking-tight text-gray-900 sm:text-4xl dark:text-white transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100" data-text="About"';

    expect($content)->toContain($fade_in)
        // Same scroll fade-in as "Relevant Experiences" and "From the Blog...".
        ->and(substr_count($content, 'intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100" data-text='))->toBe(4);
});

it('keeps the section nav hidden over the hero and reveals it past the hero', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    // The opening tag holds "=>" in its script, so match up to the list that follows it.
    preg_match('/<nav\s+aria-label="Page sections".*?<ul/s', $content, $nav);

    expect($nav)->not->toBeEmpty();

    expect($nav[0])
        // Fixed below the site header, hidden until the hero (#top) scrolls away.
        ->toContain('fixed inset-x-0 top-(--site-header-h,0px) invisible opacity-0')
        ->toContain("document.getElementById('top')")
        ->toContain('shown = ! entry.isIntersecting')
        ->toContain("'visible opacity-100 translate-y-0': shown");
});

it('links every anchor in the section nav to a section on the page', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    foreach (['about', 'experience', 'blog', 'specs'] as $id) {
        // Each anchor targets the section's heading, which also lands below the bar.
        expect($content)
            ->toContain('href="#'.$id.'"')
            ->toMatch('/<h2 id="'.$id.'" class="[^"]*scroll-mt-\(--anchor-offset\)/');

        expect(substr_count($content, 'id="'.$id.'"'))->toBe(1);
    }
});

it('picks the active section link from heading positions while scrolling', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    preg_match('/<nav\s+aria-label="Page sections".*?<ul/s', $content, $nav);

    expect($nav)->not->toBeEmpty();

    expect($nav[0])
        ->toContain('x-on:scroll.window.passive="pickActive()"')
        ->toContain('heading.getBoundingClientRect().top <= line')
        ->toContain('this.$el.offsetHeight + 80;')
        ->not->toContain("rootMargin: '-45% 0px -50% 0px'");
});

it('loads Geist Mono and no longer loads Chakra Petch or JetBrains Mono', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('family=Geist+Mono', false)
        ->assertSee('family=Audiowide', false)
        ->assertDontSee('Chakra+Petch', false)
        ->assertDontSee('JetBrains+Mono', false);
});

it('no longer ships the cyber reticle cursor', function () {
    expect(file_exists(resource_path('js/cyber-cursor.js')))->toBeFalse()
        ->and(file_get_contents(resource_path('js/app.js')))->not->toContain('cyber-cursor')
        ->and(file_get_contents(resource_path('css/app.css')))->not->toContain('cyber-reticle');
});

it('places a blocky background strip after the About section and each named section', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    preg_match_all('/<pattern id="(blocky-background-[0-9a-f-]{36})"/', $content, $patterns);
    preg_match_all('/fill="url\(#(blocky-background-[0-9a-f-]{36})\)"/', $content, $fills);

    // After About, Relevant Experiences, From the Blog and Current Specs.
    expect($patterns[1])->toHaveCount(4)
        // Each strip has its own pattern id, and each fill points at its own strip's pattern.
        ->and(array_unique($patterns[1]))->toHaveCount(4)
        ->and($fills[1])->toBe($patterns[1]);

    // Each strip follows a section: About, then the three sections marked "... section -->".
    expect($content)->toMatch('/<\/section>\s*<section[^>]*>\s*<div class="isolate">\s*<div class="relative isolate -z-10 h-\[240px\]">/');
    expect(substr_count(file_get_contents(resource_path('views/livewire/homepage.blade.php')), '<x-blocky-background />'))->toBe(4);
});
