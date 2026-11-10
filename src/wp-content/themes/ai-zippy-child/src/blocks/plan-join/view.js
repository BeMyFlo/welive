// Plan join form: posts to admin-ajax (action submit_plan_join) and shows the result inline.
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".pjn__form").forEach((form) => {
		const feedback = form.querySelector(".pjn__feedback");
		const button = form.querySelector(".pjn__submit");
		const label = button ? button.textContent : "";

		form.addEventListener("submit", async (event) => {
			event.preventDefault();
			feedback.hidden = true;
			feedback.classList.remove("is-error");
			if (button) {
				button.disabled = true;
				button.textContent = "…";
			}
			try {
				const response = await fetch(form.action, { method: "POST", body: new FormData(form) });
				const data = await response.json();
				if (data && data.success) {
					feedback.textContent = form.dataset.success || "";
					form.reset();
				} else {
					feedback.textContent = (data && data.data && data.data.message) || form.dataset.error || "";
					feedback.classList.add("is-error");
				}
			} catch (e) {
				feedback.textContent = form.dataset.error || "";
				feedback.classList.add("is-error");
			}
			feedback.hidden = false;
			if (button) {
				button.disabled = false;
				button.textContent = label;
			}
		});
	});
});
