<?php
defined('ABSPATH') || exit;

$title    = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
$slides   = array_values($attributes['slides'] ?? []);
$kicker   = $attributes['kicker'] ?? '';
$cta_text = $attributes['ctaText'] ?? '';
$cta_url  = $attributes['ctaUrl'] ?? '/artists/';
$text_align = $attributes['textAlign'] ?? 'left';
$total    = count($slides);

$wrapper = get_block_wrapper_attributes([
	'class'         => 'hhs has-text-align-' . $text_align,
	'data-interval' => '6000',
	'data-total'    => (string) $total,
]);
?>
<section <?php echo $wrapper; ?> aria-roledescription="carousel" aria-label="<?php echo esc_attr(wp_strip_all_tags($kicker ?: 'Welive')); ?>">
	<div class="hhs__pictures">
		<?php foreach ($slides as $i => $slide) :
			$id  = (int) ($slide['imageId'] ?? 0);
			$src = ($id ? wp_get_attachment_image_url($id, 'full') : '') ?: ($slide['imageUrl'] ?? '');
			$pos = $slide['position'] ?? '50% 40%';
			$name = wp_strip_all_tags($slide['artistName'] ?? ''); ?>
			<figure class="hhs__panel<?php echo $i === 0 ? ' is-active' : ''; ?>" id="hhs-panel-<?php echo (int) $i; ?>">
				<?php if ($src) : ?>
					<img class="hhs__slide-img" src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($name ?: wp_strip_all_tags($title)); ?>" style="object-position:<?php echo esc_attr($pos); ?>"<?php echo $i === 0 ? ' fetchpriority="high"' : ' loading="lazy"'; ?> />
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
	</div>

	<div class="hhs__content">
		<div class="hhs__content-left">
			<?php if ($kicker !== '') : ?><p class="hhs__kicker"><?php echo wp_kses_post($kicker); ?></p><?php endif; ?>
			<div class="hhs__texts">
				<?php foreach ($slides as $i => $slide) :
					$slide_title    = !empty($slide['title']) ? $slide['title'] : $title;
					$slide_subtitle = $slide['subtitle'] ?? ''; ?>
					<div class="hhs__slide-text<?php echo $i === 0 ? ' is-active' : ''; ?>" data-slide-index="<?php echo (int) $i; ?>">
						<h1 class="hhs__title"><?php echo wp_kses_post($slide_title); ?></h1>
						<?php if ($slide_subtitle !== '') : ?><p class="hhs__subtitle"><?php echo wp_kses_post($slide_subtitle); ?></p><?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ($cta_text !== '') : ?>
			<a class="hhs__cta" href="<?php echo esc_url(str_starts_with($cta_url, '/') ? home_url($cta_url) : $cta_url); ?>">
				<span class="hhs__cta-label"><?php echo wp_kses_post($cta_text); ?></span>
				<span class="hhs__cta-icon" aria-hidden="true">↗︎</span>
			</a>
		<?php endif; ?>
	</div>

	<?php if ($total > 1) : ?>
		<div class="hhs__controls">
			<div class="hhs__tabs" role="tablist" aria-label="Chọn slide">
				<?php foreach ($slides as $i => $slide) : ?>
					<button type="button" role="tab" class="hhs__tab" aria-label="<?php echo esc_attr(wp_strip_all_tags($slide['artistName'] ?? ('Slide ' . ($i + 1)))); ?>" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-controls="hhs-panel-<?php echo (int) $i; ?>" tabindex="<?php echo $i === 0 ? '0' : '-1'; ?>"><span></span></button>
				<?php endforeach; ?>
			</div>
			<button type="button" class="hhs__pause" aria-label="Tạm dừng chuyển ảnh" aria-pressed="false"><svg class="hhs__ico-pause" width="12" height="12" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true"><rect x="2" y="1" width="3" height="10" rx="0.8"/><rect x="7" y="1" width="3" height="10" rx="0.8"/></svg><svg class="hhs__ico-play" width="12" height="12" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true"><path d="M3 1.4v9.2a.6.6 0 0 0 .9.5l7.3-4.6a.6.6 0 0 0 0-1L3.9.9A.6.6 0 0 0 3 1.4z"/></svg></button>
			<span class="hhs__count" aria-hidden="true">01 / <?php echo esc_html(str_pad((string) $total, 2, '0', STR_PAD_LEFT)); ?></span>
		</div>
	<?php endif; ?>
</section>
