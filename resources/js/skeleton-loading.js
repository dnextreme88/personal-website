// Function to replace the skeleton with actual content
const showContent = containerId => {
    const container = document.getElementById(containerId);

    if (container) {
        console.log('if container', container);
        const skeleton = container.querySelector('.skeleton-loader');
        const actual = container.querySelector('.actual-content');

        if (skeleton) skeleton.classList.add('hidden');
        if (actual) actual.classList.remove('hidden');
    }
}

// Example of how you might use an Intersection Observer to trigger this
// (For simplicity, this assumes the content is loaded/ready when triggered)
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const containerId = entry.target.id;

            console.log('containerId:', containerId)
            // In a real app, you'd fetch data here and call showContent after data fetch is complete
            // For this example, we'll simulate an immediate switch
            setTimeout(() => {
                showContent(containerId)
                observer.unobserve(entry.target); // Stop observing once loaded
                console.log('i am intersecting!')
            }, 750);
        }
    });
});

// Observe all lazy-card elements
document.querySelectorAll('.lazy-card').forEach(card => {
    observer.observe(card);
});
