document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.cwl').forEach((root) => {
		const track = root.querySelector('.cwl__track');
		const prev = root.querySelector('.cwl__arrow--prev');
		const next = root.querySelector('.cwl__arrow--next');
		if (!track || !prev || !next) return;

		const step = () => Math.max(track.clientWidth * 0.8, 200);
		const sync = () => {
			prev.disabled = track.scrollLeft <= 4;
			next.disabled = track.scrollLeft >= track.scrollWidth - track.clientWidth - 4;
		};

		prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
		next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
		track.addEventListener('scroll', sync, { passive: true });
		window.addEventListener('resize', sync);
		sync();
	});
});
