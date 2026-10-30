// =============================================================================
// Scroll Reveal Module
// =============================================================================
// Animates elements into view as they scroll onto the screen.
// Uses IntersectionObserver for high performance (60fps) and zero lag.
//
// Usage:
// 1. Add class 'reveal-on-scroll' and one of:
//    - 'reveal-fade-up'
//    - 'reveal-fade-in'
//    - 'reveal-slide-left'
//    - 'reveal-slide-right'
//    - 'reveal-zoom-in'
// 2. Add 'data-reveal-delay="200"' (milliseconds) for delayed reveal.
// 3. Stagger container: Add 'data-reveal-stagger=".reveal-item"' and
//    'data-reveal-delay-base="100"' on a container to animate children.
// =============================================================================

export function initScrollReveal() {
	if (!("IntersectionObserver" in window)) {
		// Fallback for older browsers: show all immediately
		document.querySelectorAll(".reveal-on-scroll, .reveal-item").forEach(el => {
			el.classList.add("is-revealed");
		});
		return;
	}

	const observerOptions = {
		root: null,
		rootMargin: "0px 0px -50px 0px", // Trigger slightly before entering view
		threshold: 0.05 // Trigger as soon as 5% is visible
	};

	const observer = new IntersectionObserver((entries, observerInstance) => {
		entries.forEach(entry => {
			if (!entry.isIntersecting) return;

			const target = entry.target;

			// Handle stagger container
			if (target.hasAttribute("data-reveal-stagger")) {
				const selector = target.getAttribute("data-reveal-stagger") || ".reveal-item";
				const delayBase = parseInt(target.getAttribute("data-reveal-delay-base") || "100", 10);
				const items = target.querySelectorAll(selector);

				items.forEach((item, index) => {
					setTimeout(() => {
						item.classList.add("is-revealed");
					}, index * delayBase);
				});
			} else {
				// Handle individual item
				const delay = parseInt(target.getAttribute("data-reveal-delay") || "0", 10);
				if (delay > 0) {
					setTimeout(() => {
						target.classList.add("is-revealed");
					}, delay);
				} else {
					target.classList.add("is-revealed");
				}
			}

			// Stop observing once animated
			observerInstance.unobserve(target);
		});
	}, observerOptions);

	// Observe individual reveal items or stagger containers
	const targets = document.querySelectorAll(".reveal-on-scroll, [data-reveal-stagger]");
	targets.forEach(target => {
		observer.observe(target);
	});
}
