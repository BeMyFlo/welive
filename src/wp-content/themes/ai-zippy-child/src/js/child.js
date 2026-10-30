// =============================================================================
// Child theme entry — client-specific JS goes here.
// The parent theme (ai-zippy) handles core behaviors; use this file only for
// client overrides or new features that don't belong in core.
// =============================================================================

// Example:
// import "./modules/client-hero.js";

console.log("[ai-zippy-child] loaded");

document.addEventListener("DOMContentLoaded", () => {
	// 1. Countdown Timer
	const countdownEl = document.querySelector(".coming-soon-countdown");
	if (countdownEl) {
		const targetDateStr = countdownEl.getAttribute("data-date") || "2026-10-31T23:59:59";
		const targetDate = new Date(targetDateStr).getTime();

		const daysVal = document.getElementById("days");
		const hoursVal = document.getElementById("hours");
		const minutesVal = document.getElementById("minutes");
		const secondsVal = document.getElementById("seconds");

		const updateCountdown = () => {
			const now = new Date().getTime();
			const difference = targetDate - now;

			if (difference < 0) {
				if (daysVal) daysVal.innerText = "00";
				if (hoursVal) hoursVal.innerText = "00";
				if (minutesVal) minutesVal.innerText = "00";
				if (secondsVal) secondsVal.innerText = "00";
				return;
			}

			const days = Math.floor(difference / (1000 * 60 * 60 * 24));
			const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
			const seconds = Math.floor((difference % (1000 * 60)) / 1000);

			if (daysVal) daysVal.innerText = days < 10 ? "0" + days : days;
			if (hoursVal) hoursVal.innerText = hours < 10 ? "0" + hours : hours;
			if (minutesVal) minutesVal.innerText = minutes < 10 ? "0" + minutes : minutes;
			if (secondsVal) secondsVal.innerText = seconds < 10 ? "0" + seconds : seconds;
		};

		updateCountdown();
		setInterval(updateCountdown, 1000);
	}

	// 2. Subscribe Form AJAX
	const form = document.querySelector(".coming-soon-subscribe-form");
	if (form) {
		form.addEventListener("submit", (e) => {
			e.preventDefault();
			const emailInput = form.querySelector('input[type="email"]');
			const submitBtn = form.querySelector('button[type="submit"]');
			const successMsg = form.querySelector(".subscribe-message.success");
			const errorMsg = form.querySelector(".subscribe-message.error");

			if (!emailInput) return;

			const email = emailInput.value;
			
			// Hide messages
			if (successMsg) successMsg.style.display = "none";
			if (errorMsg) errorMsg.style.display = "none";
			
			// Disable button
			if (submitBtn) {
				submitBtn.disabled = true;
				submitBtn.innerText = "Sending...";
			}

			const formData = new FormData();
			formData.append("action", "submit_subscribe_form");
			formData.append("email", email);

			// Use admin-ajax URL
			const ajaxUrl = "/wp-admin/admin-ajax.php";

			fetch(ajaxUrl, {
				method: "POST",
				body: formData
			})
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					if (successMsg) {
						successMsg.innerText = data.data.message || "Subscribed successfully!";
						successMsg.style.display = "block";
					}
					form.reset();
				} else {
					if (errorMsg) {
						errorMsg.innerText = data.data.message || "Error subscribing.";
						errorMsg.style.display = "block";
					}
				}
			})
			.catch(err => {
				if (errorMsg) {
					errorMsg.innerText = "Connection error. Please try again later.";
					errorMsg.style.display = "block";
				}
			})
			.finally(() => {
				if (submitBtn) {
					submitBtn.disabled = false;
					submitBtn.innerText = "Notify Me";
				}
			});
		});
	}
});

