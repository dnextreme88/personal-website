// Scramble / decode reveal — shows a [data-cyber-text] element (and the
// homepage spec values) as random glyphs, then resolves it to the real text
// once it scrolls into view. The scrambled placeholder is set up front so the
// decode visibly "starts" on intersect. No-op under prefers-reduced-motion.

const CYBER_SCRAMBLE_CHARS = '!<>-_\\/[]{}=+*^?#01';

function randomGlyph() {
    return CYBER_SCRAMBLE_CHARS[Math.floor(Math.random() * CYBER_SCRAMBLE_CHARS.length)];
}

function scrambleString(text) {
    let out = '';

    for (let i = 0; i < text.length; i++) {
        out += text[i] === ' ' ? ' ' : randomGlyph();
    }

    return out;
}

function scrambleReveal(el) {
    const finalText = el.getAttribute('data-cyber-text') || el.textContent;
    const len = finalText.length;
    const duration = Math.min(1200, 300 + len * 25);
    const start = performance.now();

    function frame(now) {
        const progress = Math.min((now - start) / duration, 1);
        const settled = Math.floor(progress * len);
        let out = '';

        for (let i = 0; i < len; i++) {
            if (i < settled || finalText[i] === ' ') {
                out += finalText[i];
            } else {
                out += randomGlyph();
            }
        }

        el.textContent = out;

        if (progress < 1) {
            requestAnimationFrame(frame);
        } else {
            el.textContent = finalText;
        }
    }

    console.log('duration:', duration)

    requestAnimationFrame(frame);
}

function initCyberText() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const targets = document.querySelectorAll('[data-cyber-text], .cyber-text');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting && !entry.target.dataset.cyberDone) {
                entry.target.dataset.cyberDone = '1';
                scrambleReveal(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    targets.forEach((el) => {
        if (el.dataset.cyberInit) {
            return;
        }

        el.dataset.cyberInit = '1';

        if (!el.getAttribute('data-cyber-text')) {
            el.setAttribute('data-cyber-text', el.textContent);
        }

        // Show scrambled glyphs until the element scrolls into view.
        el.textContent = scrambleString(el.getAttribute('data-cyber-text'));

        observer.observe(el);
    });
}

document.addEventListener('livewire:navigated', initCyberText);
