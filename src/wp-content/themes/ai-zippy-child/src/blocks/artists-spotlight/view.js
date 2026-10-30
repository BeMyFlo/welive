document.addEventListener('DOMContentLoaded', () => {
	const cards = document.querySelectorAll('.as__card');
	
	cards.forEach((card, index) => {
		card.addEventListener('click', (e) => {
			// Do not open modal if social buttons on the card are clicked directly
			if (e.target.closest('.as__social-btn') || e.target.closest('.as__social-icons')) {
				return;
			}
			
			const modal = document.getElementById(`as-modal-${index}`);
			if (modal) {
				modal.classList.add('is-open');
				document.body.classList.add('as-modal-active');
			}
		});
	});

	const closeButtons = document.querySelectorAll('.as__modal-close');
	closeButtons.forEach(btn => {
		btn.addEventListener('click', () => {
			const modal = btn.closest('.as__modal');
			if (modal) {
				modal.classList.remove('is-open');
				document.body.classList.remove('as-modal-active');
			}
		});
	});

	const overlays = document.querySelectorAll('.as__modal-overlay');
	overlays.forEach(overlay => {
		overlay.addEventListener('click', () => {
			const modal = overlay.closest('.as__modal');
			if (modal) {
				modal.classList.remove('is-open');
				document.body.classList.remove('as-modal-active');
			}
		});
	});
});
