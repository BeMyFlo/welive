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

// ---- Wall of Honor (layout "wall"): profile dialog ----
document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.aw').forEach((root) => {
		const dialog = root.querySelector('.aw__dialog');
		if (!dialog) return;
		const visual = dialog.querySelector('.aw__visual');
		let trigger = null;

		const open = (btn) => {
			trigger = btn;
			const d = btn.dataset;
			visual.replaceChildren();
			if (d.image) {
				const img = document.createElement('img');
				img.src = d.image;
				img.alt = d.name;
				img.style.objectPosition = d.position || '50% 50%';
				visual.append(img);
			} else {
				const pending = document.createElement('span');
				pending.className = 'aw__pending';
				const ini = document.createElement('span');
				ini.className = 'aw__initials';
				ini.textContent = d.initials;
				const small = document.createElement('small');
				small.textContent = dialog.dataset.pending || '';
				pending.append(ini, small);
				visual.append(pending);
			}
			dialog.querySelector('.aw__eyebrow').textContent = dialog.dataset.eyebrow || '';
			dialog.querySelector('.aw__dname').textContent = d.name;
			dialog.querySelector('.aw__bio').textContent = d.bio || dialog.dataset.fallback || '';
			const links = dialog.querySelector('.aw__links');
			links.replaceChildren();
			let list = [];
			try { list = JSON.parse(d.links || '[]'); } catch (e) { list = []; }
			list.forEach((l) => {
				const a = document.createElement('a');
				a.href = l.url;
				a.target = '_blank';
				a.rel = 'noopener noreferrer';
				a.textContent = l.label;
				links.append(a);
			});
			dialog.showModal();
			document.body.style.overflow = 'hidden';
		};

		root.querySelectorAll('.aw__trigger').forEach((btn) => btn.addEventListener('click', () => open(btn)));
		dialog.querySelector('.aw__close').addEventListener('click', () => dialog.close());
		dialog.addEventListener('click', (e) => { if (e.target === dialog) dialog.close(); });
		dialog.addEventListener('close', () => {
			document.body.style.overflow = '';
			trigger?.focus({ preventScroll: true });
		});
	});
});
