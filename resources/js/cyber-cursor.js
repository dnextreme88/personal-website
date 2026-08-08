// Cyber reticle — a neon crosshair that trails the real mouse pointer. The
// native cursor is kept underneath (we never set `cursor: none`), so this is
// purely decorative and can't break click targets. It expands and switches to
// magenta over interactive elements.
//
// Guards: only runs on a fine pointer (desktop mouse) AND when the user has not
// asked for reduced motion — otherwise nothing is created and the native cursor
// is left completely alone.
//
// Livewire `wire:navigate` swaps the <body> on each page change, which strips
// this dynamically-appended element (it isn't part of any page's server HTML).
// So instead of a one-shot init guard, we keep a single element + document-level
// listeners (those survive the swap) and simply re-append the element whenever a
// navigation has detached it.

const INTERACTIVE = 'a, button, input, select, textarea, label, [role="button"], .cursor-pointer, [wire\\:click], [x-on\\:click]';

let reticle = null;

// Target position (real cursor) vs rendered position (lerped for a trail).
let targetX = window.innerWidth / 2;
let targetY = window.innerHeight / 2;
let renderX = targetX;
let renderY = targetY;
let visible = false;

function buildReticle() {
    reticle = document.createElement('div');
    reticle.className = 'cyber-reticle';
    reticle.setAttribute('aria-hidden', 'true');

    function render() {
        if (reticle) {
            renderX += (targetX - renderX) * 0.25;
            renderY += (targetY - renderY) * 0.25;
            reticle.style.transform = `translate(${renderX}px, ${renderY}px)`;
        }

        requestAnimationFrame(render);
    }

    requestAnimationFrame(render);

    // Listeners live on `document`, which persists across wire:navigate swaps,
    // so they're attached exactly once alongside the element's creation.
    document.addEventListener('mousemove', (event) => {
        targetX = event.clientX;
        targetY = event.clientY;

        if (!visible) {
            visible = true;
            reticle.classList.add('is-visible');
        }

        const overInteractive = event.target instanceof Element && event.target.closest(INTERACTIVE);
        reticle.classList.toggle('is-hover', Boolean(overInteractive));
    });

    // Hide when the pointer leaves the document (e.g. into browser chrome).
    document.addEventListener('mouseleave', () => {
        visible = false;

        if (reticle) {
            reticle.classList.remove('is-visible');
        }
    });

    document.addEventListener('mouseenter', () => {
        visible = true;

        if (reticle) {
            reticle.classList.add('is-visible');
        }
    });
}

function initCyberCursor() {
    const finePointer = window.matchMedia('(pointer: fine)').matches;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!finePointer || reduceMotion) {
        return;
    }

    if (!reticle) {
        buildReticle();
    }

    // Re-attach after a wire:navigate swap (or first run) removed it from the DOM.
    if (!reticle.isConnected) {
        document.body.appendChild(reticle);
    }
}

document.addEventListener('livewire:navigated', initCyberCursor);
document.addEventListener('DOMContentLoaded', initCyberCursor);
