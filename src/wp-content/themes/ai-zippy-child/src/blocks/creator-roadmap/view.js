document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.crm').forEach((root) => {
		const tabs = root.querySelectorAll('.crm__tab');
		const panels = root.querySelectorAll('.crm__panel');

		tabs.forEach((tab, index) => {
			tab.addEventListener('click', () => {
				tabs.forEach((t, i) => {
					t.classList.toggle('is-active', i === index);
					t.setAttribute('aria-selected', i === index ? 'true' : 'false');
				});
				panels.forEach((p, i) => p.classList.toggle('is-active', i === index));
			});
		});
	});
});
