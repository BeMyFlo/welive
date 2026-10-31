<?php

defined('ABSPATH') || exit;

$title       = $attributes['title'] ?? '';
$subtitle    = $attributes['subtitle'] ?? '';
$button_text = $attributes['buttonText'] ?? '';
$button_url  = $attributes['buttonUrl'] ?? '';
$divisions   = $attributes['divisions'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'abec']);
?>
<div <?php echo $wrapper; ?>>
	<div class="abec__container">
		<div class="abec__header reveal-on-scroll reveal-fade-up">
			<div class="abec__intro">
				<h2 class="abec__title"><?php echo esc_html($title); ?></h2>
				<?php if ($subtitle !== '') : ?>
					<p class="abec__subtitle"><?php echo wp_kses_post($subtitle); ?></p>
				<?php endif; ?>
			</div>
			<?php if ($button_text !== '') : ?>
				<a class="abec__btn" href="<?php echo esc_url($button_url ?: '#'); ?>">
					<span><?php echo esc_html($button_text); ?></span>
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M7 17L17 7M17 7H8M17 7V16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</a>
			<?php endif; ?>
		</div>

		<div class="abec__grid" data-reveal-stagger=".abec__item" data-reveal-delay-base="100">
			<?php foreach ($divisions as $div) :
				$name      = $div['name'] ?? '';
				$desc      = $div['description'] ?? '';
				$image_url = $div['imageUrl'] ?? '';
			?>
				<div class="abec__item reveal-item reveal-fade-up">
					<div class="abec__media">
						<?php if ($image_url) : ?>
							<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" />
						<?php endif; ?>
					</div>
					<h3 class="abec__name"><?php echo esc_html($name); ?></h3>
					<p class="abec__desc"><?php echo wp_kses_post(nl2br($desc)); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
