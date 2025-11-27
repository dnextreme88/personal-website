// Alpine.js component for skeleton loading
Alpine.data('skeletonLoader', () => ({
  isLoaded: false,
  intersectionObserver: null,

  init() {
    this.setupIntersectionObserver();
  },

  setupIntersectionObserver() {
    const options = {
      root: null,
      rootMargin: '0px',
      threshold: 0.1
    };

    this.intersectionObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !this.isLoaded) {
          // Trigger loading after a slight delay (simulating data fetch)
          setTimeout(() => {
            this.isLoaded = true;
            this.intersectionObserver.unobserve(entry.target);
            console.log('I am intersecting!', entry.target.id);
          }, 500);
        }
      });
    }, options);

    // Observe the element
    this.intersectionObserver.observe(this.$el);
  },

  destroy() {
    if (this.intersectionObserver) {
      this.intersectionObserver.disconnect();
    }
  }
}));
