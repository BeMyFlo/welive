// Creator story row: Prev/Next scrolling + profile dialog. Vanilla JS.
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".cst").forEach((root) => {
		const track = root.querySelector(".cst__track");
		const prev = root.querySelector(".cst__prev");
		const next = root.querySelector(".cst__next");
		const controls = root.querySelector(".cst__controls");
		const dialog = root.querySelector(".cst__dialog");
		if (!track) return;

		const update = () => {
			const end = track.scrollWidth - track.clientWidth;
			controls.hidden = end <= 2;
			prev.disabled = track.scrollLeft <= 2;
			next.disabled = track.scrollLeft >= end - 2;
		};
		const move = (dir) => {
			const gap = parseFloat(getComputedStyle(track).gap) || 0;
			const step = track.firstElementChild.getBoundingClientRect().width + gap;
			const reduce = matchMedia("(prefers-reduced-motion: reduce)").matches;
			track.scrollBy({ left: step * dir, behavior: reduce ? "instant" : "smooth" });
		};
		prev.addEventListener("click", () => move(-1));
		next.addEventListener("click", () => move(1));
		track.addEventListener("scroll", update, { passive: true });
		track.addEventListener("keydown", (e) => {
			if (e.target !== track || !["ArrowRight", "ArrowLeft"].includes(e.key)) return;
			e.preventDefault();
			move(e.key === "ArrowRight" ? 1 : -1);
		});
		new ResizeObserver(update).observe(track);
		update();

		if (!dialog) return;
		const visual = dialog.querySelector(".cst__visual");
		let trigger = null;
		root.querySelectorAll(".cst__card > button").forEach((btn) =>
			btn.addEventListener("click", () => {
				trigger = btn;
				const d = btn.dataset;
				visual.replaceChildren();
				if (d.image) {
					const img = document.createElement("img");
					img.src = d.image;
					img.alt = d.name;
					img.style.objectPosition = d.position || "50% 50%";
					visual.append(img);
				}
				dialog.querySelector(".cst__eyebrow-d").textContent = dialog.dataset.eyebrow || "";
				dialog.querySelector(".cst__dname").textContent = d.name;
				dialog.querySelector(".cst__bio").textContent = d.bio || dialog.dataset.fallback || "";
				const note = dialog.querySelector(".cst__note");
				note.textContent = dialog.dataset.note || "";
				note.hidden = !dialog.dataset.note;
				const links = dialog.querySelector(".cst__links");
				links.replaceChildren();
				let list = [];
				try { list = JSON.parse(d.links || "[]"); } catch (e) { list = []; }
				list.forEach((l) => {
					const a = document.createElement("a");
					a.href = l.url;
					a.target = "_blank";
					a.rel = "noopener noreferrer";
					a.textContent = l.label;
					links.append(a);
				});
				dialog.showModal();
				document.body.style.overflow = "hidden";
			})
		);
		dialog.querySelector(".cst__close").addEventListener("click", () => dialog.close());
		dialog.addEventListener("click", (e) => { if (e.target === dialog) dialog.close(); });
		dialog.addEventListener("close", () => {
			document.body.style.overflow = "";
			trigger?.focus({ preventScroll: true });
		});
	});
});
