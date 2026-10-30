document.addEventListener('DOMContentLoaded', () => {
	const ecosystems = document.querySelectorAll('.wp-block-ai-zippy-child-about-ecosystem');

	ecosystems.forEach((eco) => {
		const tabButtons = eco.querySelectorAll('.abec__tab-btn');
		const tabPanels = eco.querySelectorAll('.abec__tab-panel');

		tabButtons.forEach((btn) => {
			btn.addEventListener('click', () => {
				const index = parseInt(btn.getAttribute('data-index'), 10);

				// Remove active class from all buttons
				tabButtons.forEach((b) => b.classList.remove('abec__tab-btn--active'));
				// Add active class to clicked button
				btn.classList.add('abec__tab-btn--active');

				// Hide all panels
				tabPanels.forEach((panel) => {
					panel.classList.remove('abec__tab-panel--active');
					const panelIdx = parseInt(panel.getAttribute('data-panel'), 10);
					if (panelIdx === index) {
						panel.classList.add('abec__tab-panel--active');
					}
				});
			});
		});
	});
});
