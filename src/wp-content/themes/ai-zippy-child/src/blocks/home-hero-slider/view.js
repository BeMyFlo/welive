document.addEventListener('DOMContentLoaded', () => {
	const sliders = document.querySelectorAll('.wp-block-ai-zippy-child-home-hero-slider');
	
	sliders.forEach((slider) => {
		const swiperEl = slider.querySelector('.hhs__swiper');
		if (!swiperEl) return;

		// Function to initialize once Swiper class is loaded
		const initSlider = () => {
			if (typeof Swiper === 'undefined') {
				// Retry in 100ms if Swiper script from CDN is not loaded yet
				setTimeout(initSlider, 100);
				return;
			}

			const currentCounter = slider.querySelector('.hhs__counter-current');

			const updateSlideText = (activeIndex) => {
				const slideTexts = slider.querySelectorAll('.hhs__slide-text');
				slideTexts.forEach((textEl) => {
					const slideIndex = parseInt(textEl.getAttribute('data-slide-index'), 10);
					if (slideIndex === activeIndex) {
						textEl.classList.add('is-active');
					} else {
						textEl.classList.remove('is-active');
					}
				});
			};

			new Swiper(swiperEl, {
				loop: true,
				speed: 1000,
				autoplay: {
					delay: 6000,
					disableOnInteraction: false,
				},
				effect: 'fade',
				fadeEffect: {
					crossFade: true
				},
				navigation: {
					nextEl: slider.querySelector('.hhs__nav--next'),
					prevEl: slider.querySelector('.hhs__nav--prev'),
				},
				on: {
					init: function () {
						if (currentCounter) {
							currentCounter.textContent = String(this.realIndex + 1).padStart(2, '0');
						}
						updateSlideText(this.realIndex);
					},
					slideChange: function () {
						if (currentCounter) {
							currentCounter.textContent = String(this.realIndex + 1).padStart(2, '0');
						}
						updateSlideText(this.realIndex);
					}
				}
			});
		};

		initSlider();
	});
});
