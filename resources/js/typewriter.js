// Typewriter — types a [data-typewriter] element out character by character,
// but only once it scrolls into view. Options via data attributes:
//   data-typewriter          the text to type (falls back to the element text)
//   data-typewriter-once     type a single pass instead of looping
//   data-typewriter-speed    per-character delay in ms (default 110)
// Pairs with the .blinking-cursor class for the trailing caret. Under
// prefers-reduced-motion the final text is shown static with no animation.

function typewriterType(el, loop, typeSpeed) {
    const finalText = el.getAttribute('data-typewriter');
    const holdFull = 3000;
    const holdEmpty = 400;
    let i = 0;

    function tick() {
        el.textContent = finalText.slice(0, i);

        if (i < finalText.length) {
            i += 1;
            setTimeout(tick, typeSpeed);
        } else if (loop) {
            setTimeout(() => {
                i = 0;
                setTimeout(tick, holdEmpty);
            }, holdFull);
        }
    }

    tick();
}

function initTypewriter() {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('[data-typewriter]').forEach((el) => {
        if (el.dataset.twInit) {
            return;
        }

        el.dataset.twInit = '1';

        if (!el.getAttribute('data-typewriter')) {
            el.setAttribute('data-typewriter', el.textContent.trim());
        }

        const loop = !el.hasAttribute('data-typewriter-once');
        const typeSpeed = parseInt(el.getAttribute('data-typewriter-speed'), 10) || 100;

        if (reduce) {
            el.textContent = el.getAttribute('data-typewriter');

            return;
        }

        // Reserve the current height so clearing the text doesn't shift the
        // layout, then start typing only once the element scrolls into view.
        el.style.minHeight = el.offsetHeight + 'px';
        el.textContent = '';

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    observer.disconnect();
                    typewriterType(el, loop, typeSpeed);
                }
            });
        }, { threshold: 0.3 });

        observer.observe(el);
    });
}

document.addEventListener('livewire:navigated', initTypewriter);
