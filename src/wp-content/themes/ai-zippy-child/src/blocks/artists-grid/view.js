document.addEventListener('DOMContentLoaded', () => {
	const gridContainers = document.querySelectorAll('.wp-block-ai-zippy-child-artists-grid');

	gridContainers.forEach((container) => {
		const cards = container.querySelectorAll('.ag__card');
		const popups = container.querySelectorAll('.ag__popup-wrap');

		cards.forEach((card) => {
			card.addEventListener('click', () => {
				const index = card.getAttribute('data-artist-index');
				const popup = container.querySelector(`#ag-popup-${index}`);
				if (popup) {
					popup.classList.add('ag__popup-wrap--active');
					document.body.style.overflow = 'hidden';
				}
			});
		});

		popups.forEach((popup) => {
			const closeBtn = popup.querySelector('.ag__popup-close');
			const overlay = popup.querySelector('.ag__popup-overlay');

			const closePopup = () => {
				popup.classList.remove('ag__popup-wrap--active');
				// Check if any other popups are still active before restoring body overflow
				const activePopups = container.querySelectorAll('.ag__popup-wrap--active');
				if (activePopups.length === 0) {
					document.body.style.overflow = '';
				}
			};

			if (closeBtn) closeBtn.addEventListener('click', closePopup);
			if (overlay) overlay.addEventListener('click', closePopup);
		});
	});
});
