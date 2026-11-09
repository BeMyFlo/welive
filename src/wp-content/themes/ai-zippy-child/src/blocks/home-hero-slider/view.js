// Home hero slider: fade between panels, dot tabs, pause button, counter. Vanilla JS, no dependencies.
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".hhs").forEach((root) => {
		const panels = [...root.querySelectorAll(".hhs__panel")];
		const texts = [...root.querySelectorAll(".hhs__slide-text")];
		const tabs = [...root.querySelectorAll(".hhs__tab")];
		const pauseBtn = root.querySelector(".hhs__pause");
		const count = root.querySelector(".hhs__count");
		const total = panels.length;
		if (total < 2) return;

		const interval = parseInt(root.dataset.interval, 10) || 6000;
		const reduced = window.matchMedia("(prefers-reduced-motion: reduce)");
		let current = 0;
		let paused = reduced.matches;
		let timer = null;

		const pad = (n) => String(n).padStart(2, "0");

		const show = (index, focus = false) => {
			current = (index + total) % total;
			panels.forEach((el, i) => el.classList.toggle("is-active", i === current));
			texts.forEach((el, i) => el.classList.toggle("is-active", i === current));
			tabs.forEach((tab, i) => {
				tab.setAttribute("aria-selected", String(i === current));
				tab.tabIndex = i === current ? 0 : -1;
			});
			if (count) count.textContent = `${pad(current + 1)} / ${pad(total)}`;
			if (focus && tabs[current]) tabs[current].focus();
		};

		const sync = () => {
			clearInterval(timer);
			if (pauseBtn) {
				pauseBtn.setAttribute("aria-pressed", String(paused));
				pauseBtn.setAttribute("aria-label", paused ? "Tiếp tục chuyển ảnh" : "Tạm dừng chuyển ảnh");
				pauseBtn.textContent = paused ? "▶" : "Ⅱ";
			}
			if (!paused && !document.hidden) {
				timer = setInterval(() => {
					if (!root.matches(":hover") && !root.contains(document.activeElement)) show(current + 1);
				}, interval);
			}
		};

		tabs.forEach((tab, index) => {
			tab.addEventListener("click", () => {
				show(index);
				sync();
			});
			tab.addEventListener("keydown", (event) => {
				const map = { ArrowLeft: current - 1, ArrowRight: current + 1, Home: 0, End: total - 1 };
				if (!(event.key in map)) return;
				event.preventDefault();
				show(map[event.key], true);
				sync();
			});
		});

		pauseBtn?.addEventListener("click", () => {
			paused = !paused;
			sync();
		});
		document.addEventListener("visibilitychange", sync);
		reduced.addEventListener("change", (e) => {
			paused = e.matches;
			sync();
		});

		sync();
	});
});
