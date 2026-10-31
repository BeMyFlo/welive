<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

// Enqueue Swiper JS and CSS from CDN for frontend slider
wp_enqueue_style('swiper-cdn', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11.0.0');
wp_enqueue_script('swiper-cdn', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11.0.0', true);

$title    = $attributes['title'] ?? 'BOLD MUSIC BOLD FUTURE';
$subtitle = $attributes['subtitle'] ?? '';
$slides   = $attributes['slides'] ?? [];

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'hhs has-text-align-' . $text_align]);
?>
<div <?php echo $wrapper_attributes; ?>>
	<!-- Swiper Container -->
	<div class="hhs__swiper swiper">
		<div class="swiper-wrapper">
			<?php if (!empty($slides)) : ?>
				<?php foreach ($slides as $index => $slide) : 
					$image_url   = $slide['imageUrl'] ?? '';
					$artist_name = $slide['artistName'] ?? '';
					$link        = $slide['link'] ?? '';
				?>
					<div class="hhs__swiper-slide swiper-slide">
						<?php if ($image_url) : ?>
							<img class="hhs__slide-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($artist_name ?: $title); ?>" />
						<?php endif; ?>
						<div class="hhs__overlay"></div>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<!-- Placeholder Slide if empty -->
				<div class="hhs__swiper-slide swiper-slide">
					<div class="hhs__overlay"></div>
				</div>
			<?php endif; ?>
		</div>

		<!-- Navigation Arrows -->
		<div class="hhs__nav hhs__nav--prev">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</div>
		<div class="hhs__nav hhs__nav--next">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</div>

		<!-- Content Overlays (will update with Swiper slide change) -->
		<div class="hhs__content">
			<div class="hhs__content-left">
				<?php foreach ($slides as $index => $slide) : 
					$slide_title    = !empty($slide['title']) ? $slide['title'] : $title;
					$slide_subtitle = !empty($slide['subtitle']) ? $slide['subtitle'] : $subtitle;
				?>
					<div class="hhs__slide-text <?php echo $index === 0 ? 'is-active' : ''; ?>" data-slide-index="<?php echo $index; ?>">
						<h1 class="hhs__title"><?php echo wp_kses_post($slide_title); ?></h1>
						<p class="hhs__subtitle"><?php echo wp_kses_post($slide_subtitle); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- CTA -->
			<a class="hhs__cta" href="<?php echo esc_url(home_url('/artists/')); ?>">
				<span class="hhs__cta-label">Khám phá nghệ sĩ</span>
				<svg class="hhs__cta-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M7 17L17 7M17 7H8M17 7V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</div>
	</div>
</div>
