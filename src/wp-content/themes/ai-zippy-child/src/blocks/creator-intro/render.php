<?php
defined('ABSPATH') || exit;

$badge          = $attributes['badge'] ?? '';
$title          = $attributes['title'] ?? '';
$title_accent   = $attributes['titleAccent'] ?? '';
$description    = $attributes['description'] ?? '';
$primary_text   = $attributes['primaryText'] ?? '';
$primary_url    = $attributes['primaryUrl'] ?? '';
$secondary_text = $attributes['secondaryText'] ?? '';
$secondary_url  = $attributes['secondaryUrl'] ?? '';
$meta_text      = $attributes['metaText'] ?? '';
$image_url      = $attributes['imageUrl'] ?? '';
$caption_title  = $attributes['captionTitle'] ?? '';
$caption_text   = $attributes['captionText'] ?? '';

// Root-relative links ("/page/") are resolved against the site URL.
$resolve = static fn(string $url): string => $url === '' ? '#' : (str_starts_with($url, '/') ? home_url($url) : $url);

$wrapper = get_block_wrapper_attributes(['class' => 'cin']);
?>
<div <?php echo $wrapper; ?>>
	<div class="cin__container">
		<div class="cin__text reveal-on-scroll reveal-fade-up">
			<?php if ($badge !== '') : ?>
				<span class="cin__badge"><svg class="cin__badge-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.8 4.7L18.5 9.5l-4.7 1.8L12 16l-1.8-4.7L5.5 9.5l4.7-1.8z"/><path d="M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8z"/></svg><span><?php echo wp_kses_post($badge); ?></span></span>
			<?php endif; ?>
			<h2 class="cin__title"><?php echo wp_kses_post($title); ?> <span class="cin__title-accent"><?php echo wp_kses_post($title_accent); ?></span></h2>
			<?php if ($description !== '') : ?>
				<p class="cin__desc"><?php echo wp_kses_post($description); ?></p>
			<?php endif; ?>

			<div class="cin__actions">
				<?php if ($primary_text !== '') : ?>
					<a class="cin__btn" href="<?php echo esc_url($resolve($primary_url)); ?>">
						<span><?php echo wp_kses_post($primary_text); ?></span>
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17L17 7M17 7H8M17 7V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</a>
				<?php endif; ?>
				<?php if ($secondary_text !== '') : ?>
					<a class="cin__link" href="<?php echo esc_url($resolve($secondary_url)); ?>">
						<span><?php echo wp_kses_post($secondary_text); ?></span>
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M12 19l-6-6M12 19l6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</a>
				<?php endif; ?>
			</div>

			<?php if ($meta_text !== '') : ?>
				<p class="cin__meta">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="19" r="2.5"/><circle cx="18" cy="5" r="2.5"/><path d="M8.5 19H15a3.5 3.5 0 0 0 0-7H9a3.5 3.5 0 0 1 0-7h6.5"/></svg>
					<span><?php echo wp_kses_post($meta_text); ?></span>
				</p>
			<?php endif; ?>
		</div>

		<div class="cin__media reveal-on-scroll reveal-fade-up" data-reveal-delay="150">
			<?php if ($image_url) : ?>
				<img src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy" />
			<?php endif; ?>
			<?php if ($caption_title !== '' || $caption_text !== '') : ?>
				<div class="cin__caption">
					<strong><?php echo wp_kses_post($caption_title); ?></strong>
					<span><?php echo wp_kses_post($caption_text); ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
