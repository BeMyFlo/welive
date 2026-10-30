document.addEventListener('DOMContentLoaded', () => {
	const forms = document.querySelectorAll('.cf__form');

	forms.forEach((form) => {
		const container = form.closest('.cf__form-wrap');
		const successMsg = container.querySelector('.cf__status-msg--success');
		const errorMsg = container.querySelector('.cf__status-msg--error');
		const submitBtn = form.querySelector('.cf__button');

		form.addEventListener('submit', (e) => {
			e.preventDefault();

			// Hide existing alerts
			if (successMsg) successMsg.style.display = 'none';
			if (errorMsg) errorMsg.style.display = 'none';

			// Show loading/sending state
			if (submitBtn) {
				submitBtn.disabled = true;
				submitBtn.innerText = 'Sending...';
			}

			const formData = new FormData(form);

			fetch(form.action, {
				method: 'POST',
				body: formData
			})
			.then((response) => {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			})
			.then((data) => {
				if (data.success) {
					if (successMsg) {
						successMsg.innerText = data.data?.message || 'Thank you! Your submission has been received!';
						successMsg.style.display = 'block';
					}
					form.reset();
				} else {
					if (errorMsg) {
						errorMsg.innerText = data.data?.message || 'Oops! Something went wrong while submitting the form.';
						errorMsg.style.display = 'block';
					}
				}
			})
			.catch((err) => {
				console.error('Error submitting form:', err);
				if (errorMsg) {
					errorMsg.innerText = 'An error occurred. Please check your network connection and try again.';
					errorMsg.style.display = 'block';
				}
			})
			.finally(() => {
				if (submitBtn) {
					submitBtn.disabled = false;
					submitBtn.innerText = 'Submit';
				}
			});
		});
	});
});
