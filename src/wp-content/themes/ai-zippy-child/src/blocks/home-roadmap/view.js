// Home roadmap (layout "tabs"): switch the detail panel with the day tabs. Vanilla JS.
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".hrm--tabs").forEach((root) => {
		const tabs = [...root.querySelectorAll(".hrm__tab")];
		if (!tabs.length) return;

		const choose = (selected) => {
			tabs.forEach((tab) => {
				const active = tab === selected;
				tab.setAttribute("aria-selected", String(active));
				tab.tabIndex = active ? 0 : -1;
				const panel = document.getElementById(tab.getAttribute("aria-controls"));
				if (panel) panel.hidden = !active;
			});
		};

		tabs.forEach((tab, index) => {
			tab.addEventListener("click", () => choose(tab));
			tab.addEventListener("keydown", (event) => {
				const count = tabs.length;
				const target = { ArrowLeft: (index + count - 1) % count, ArrowRight: (index + 1) % count, Home: 0, End: count - 1 }[event.key];
				if (target === undefined) return;
				event.preventDefault();
				choose(tabs[target]);
				tabs[target].focus();
			});
		});
	});
});
