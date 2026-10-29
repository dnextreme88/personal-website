// Alpine.js component for the game screenshots filmstrip: glitch swap between images,
// a decoding HUD readout, a centred neon active thumbnail, and keyboard browsing.
Alpine.data('gameScreenshotsGallery', (screenshots) => ({
  screenshots,
  index: 0,
  isGlitching: false,
  isFrameLoaded: false,
  readoutLeft: '',
  readoutRight: '',
  // Read by <x-modal-image-preview> on the same element
  isImagePreviewModalOpen: false,
  imagePreviewSrc: '',
  reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
  glitchTimeout: null,

  init() {
    // A cached image can finish loading before Alpine binds x-on:load
    if (this.$refs.frameImage?.complete) {
      this.isFrameLoaded = true;
    }

    this.updateReadout();
  },

  get current() {
    return this.screenshots[this.index] ?? null;
  },

  pad(n) {
    return String(n).padStart(2, '0');
  },

  show(i) {
    const count = this.screenshots.length;

    if (!count) {
      return;
    }

    const next = (i + count) % count;

    if (next === this.index) {
      return;
    }

    this.index = next;
    this.glitch();
    this.updateReadout();
    this.$nextTick(() => this.centreActiveThumb());
  },

  next() {
    this.show(this.index + 1);
  },

  prev() {
    this.show(this.index - 1);
  },

  openPreview() {
    if (!this.current) {
      return;
    }

    this.imagePreviewSrc = this.current.url;
    this.isImagePreviewModalOpen = true;
  },

  onKey(event) {
    const target = event.target instanceof Element ? event.target : null;

    if (this.isImagePreviewModalOpen || target?.closest('input, textarea, select, [contenteditable]')) {
      return;
    }

    if (event.key === 'ArrowRight') {
      this.next();
    } else if (event.key === 'ArrowLeft') {
      this.prev();
    } else if (event.key === 'Home') {
      event.preventDefault();
      this.show(0);
    } else if (event.key === 'End') {
      event.preventDefault();
      this.show(this.screenshots.length - 1);
    }
  },

  // Restart the glitch keyframes on every change: drop the class, force a reflow, add it back
  glitch() {
    if (this.reducedMotion) {
      return;
    }

    clearTimeout(this.glitchTimeout);
    this.isGlitching = false;

    this.$nextTick(() => {
      // Alpine has removed the class by now; the reflow makes the browser see it gone before re-adding
      void this.$refs.frame?.offsetWidth;
      this.isGlitching = true;
      this.glitchTimeout = setTimeout(() => {
        this.isGlitching = false;
      }, 340);
    });
  },

  updateReadout() {
    if (!this.current) {
      return;
    }

    const count = this.pad(this.screenshots.length);

    this.decode('readoutLeft', `IMG_${this.pad(this.index + 1)} / ${count} // ${this.current.game_label}`);
    this.decode('readoutRight', this.current.date ?? 'NO DATE');
  },

  // Scramble the text for ~300ms, resolving left to right. Spaces and / - _ stay put
  decode(key, text) {
    clearInterval(this[`${key}Interval`]);

    if (this.reducedMotion) {
      this[key] = text;

      return;
    }

    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#%&*';
    const totalFrames = 12;
    let frame = 0;

    this[`${key}Interval`] = setInterval(() => {
      frame++;

      this[key] = [...text]
        .map((char, i) => (' /-_'.includes(char) || i < (text.length * frame) / totalFrames)
          ? char
          : chars[Math.floor(Math.random() * chars.length)])
        .join('');

      if (frame >= totalFrames) {
        clearInterval(this[`${key}Interval`]);
        this[key] = text;
      }
    }, 26);
  },

  // scrollTo on the strip only; scrollIntoView would also scroll the page vertically
  centreActiveThumb() {
    const strip = this.$refs.strip;
    const thumb = strip?.children[this.index];

    if (!thumb) {
      return;
    }

    strip.scrollTo({
      left: thumb.offsetLeft - strip.clientWidth / 2 + thumb.offsetWidth / 2,
      behavior: this.reducedMotion ? 'auto' : 'smooth',
    });
  },
}));
