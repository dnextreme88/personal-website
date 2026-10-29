// Alpine.js component for the dropping areas transit route: the rail fills as the page
// scrolls, and each station lights up when the fill reaches its node.
Alpine.data('droppingAreasRoute', () => ({
  // Read by <x-modal-image-preview> on the same element
  isImagePreviewModalOpen: false,
  imagePreviewSrc: '',
  reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
  frame: null,

  init() {
    this.update();
  },

  openPreview(url) {
    this.imagePreviewSrc = url;
    this.isImagePreviewModalOpen = true;
  },

  // Scroll and resize fire many times per frame; update once per animation frame only
  queueUpdate() {
    if (this.frame) {
      return;
    }

    this.frame = requestAnimationFrame(() => {
      this.frame = null;
      this.update();
    });
  },

  update() {
    const route = this.$refs.route;
    const fill = this.$refs.railFill;

    if (!route || !fill) {
      return;
    }

    const stations = route.querySelectorAll('.route-station');

    // At the bottom of the page the last station can sit below the fill mark, so fill it all
    const isAtBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2;

    if (this.reducedMotion || isAtBottom) {
      fill.style.height = '100%';
      stations.forEach((station) => station.classList.add('is-reached'));

      return;
    }

    // The fill ends a little below the middle of the viewport
    const mark = window.innerHeight * 0.62;
    const rect = route.getBoundingClientRect();

    fill.style.height = `${Math.max(0, Math.min(rect.height, mark - rect.top))}px`;

    stations.forEach((station) => {
      const node = station.querySelector('.route-node');

      station.classList.toggle('is-reached', node.getBoundingClientRect().top < mark);
    });
  },
}));
