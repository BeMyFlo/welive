// About gallery: category filter, "show more" and a native <dialog> lightbox. Vanilla JS.
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".agl").forEach((root) => {
		const cards = [...root.querySelectorAll(".agl__card")];
		const filterBtns = [...root.querySelectorAll("[data-filter]")];
		const more = root.querySelector(".agl__more");
		const status = root.querySelector(".agl__status");
		const dialog = root.querySelector(".agl__dialog");
		const img = root.querySelector(".agl__lb-img");
		const perPage = parseInt(root.dataset.perPage, 10) || 6;
		const unit = root.dataset.unit || "";
		const pad = (n) => String(n).padStart(2, "0");

		let filter = "all";
		let showAll = false;
		let visible = [];
		let index = 0;
		let trigger = null;

		const render = () => {
			const matching = cards.filter((c) => filter === "all" || c.dataset.cat === filter);
			visible = showAll ? matching : matching.slice(0, perPage);
			cards.forEach((c) => {
				c.hidden = !visible.includes(c);
			});
			if (status) status.textContent = `${visible.length} / ${matching.length} ${unit}`.trim();
			if (more) more.hidden = visible.length >= matching.length;
		};

		filterBtns.forEach((btn) =>
			btn.addEventListener("click", () => {
				filter = btn.dataset.filter;
				showAll = false;
				filterBtns.forEach((b) => b.setAttribute("aria-pressed", String(b === btn)));
				render();
			})
		);
		more?.addEventListener("click", () => {
			showAll = true;
			render();
			visible[perPage]?.querySelector("button")?.focus({ preventScroll: true });
		});

		const update = () => {
			const btn = visible[index].querySelector("button");
			img.src = btn.dataset.full;
			img.alt = btn.dataset.title;
			root.querySelector(".agl__lb-title").textContent = btn.dataset.title;
			root.querySelector(".agl__lb-desc").textContent = btn.dataset.desc;
			root.querySelector(".agl__lb-count").textContent = `${pad(index + 1)} / ${pad(visible.length)}`;
			const single = visible.length < 2;
			root.querySelector(".agl__lb-prev").hidden = single;
			root.querySelector(".agl__lb-next").hidden = single;
		};
		const step = (d) => {
			index = (index + d + visible.length) % visible.length;
			update();
		};

		root.querySelector(".agl__grid").addEventListener("click", (e) => {
			const btn = e.target.closest(".agl__card button");
			if (!btn || !dialog) return;
			trigger = btn;
			index = visible.indexOf(btn.closest(".agl__card"));
			update();
			dialog.showModal();
			document.body.style.overflow = "hidden";
		});
		root.querySelector(".agl__lb-close")?.addEventListener("click", () => dialog.close());
		root.querySelector(".agl__lb-prev")?.addEventListener("click", () => step(-1));
		root.querySelector(".agl__lb-next")?.addEventListener("click", () => step(1));
		dialog?.addEventListener("keydown", (e) => {
			if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
				e.preventDefault();
				step(e.key === "ArrowLeft" ? -1 : 1);
			}
		});
		dialog?.addEventListener("click", (e) => {
			if (e.target === dialog) dialog.close();
		});
		dialog?.addEventListener("close", () => {
			document.body.style.overflow = "";
			trigger?.focus({ preventScroll: true });
		});

		render();
	});
});
