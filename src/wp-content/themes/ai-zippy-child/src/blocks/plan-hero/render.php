<?php
defined('ABSPATH') || exit;

$anchor  = $attributes['anchor'] ?? '';
$image   = is_array($attributes['image'] ?? null) ? $attributes['image'] : [];
$img_url = !empty($image['imageId']) ? wp_get_attachment_image_url((int) $image['imageId'], 'large') : '';
$img_url = $img_url ?: ($image['imageUrl'] ?? '');
$img_pos = preg_match('/^[0-9.%\s a-z-]{1,30}$/i', (string) ($image['position'] ?? '')) ? $image['position'] : '50% 40%';
$accent  = $attributes['titleAccent'] ?? '';

$wrapper = get_block_wrapper_attributes(['class' => 'phr'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="phr__wrap">
		<div class="phr__copy">
			<?php if (!empty($attributes['pill'])) : ?>
				<span class="phr__pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/><path d="M20 2v4M22 4h-4"/><circle cx="4" cy="20" r="2"/></svg><?php echo wp_kses_post($attributes['pill']); ?></span>
			<?php endif; ?>
			<h1 class="phr__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?><?php if ($accent !== '') : ?><br><span><?php echo wp_kses_post($accent); ?></span><?php endif; ?></h1>
			<?php if (!empty($attributes['text'])) : ?><p class="phr__text"><?php echo wp_kses_post($attributes['text']); ?></p><?php endif; ?>
			<div class="phr__actions">
				<?php if (!empty($attributes['ctaText'])) : ?><a class="phr__cta" href="<?php echo esc_url($attributes['ctaUrl'] ?: '#'); ?>"><?php echo wp_kses_post($attributes['ctaText']); ?></a><?php endif; ?>
				<?php if (!empty($attributes['linkText'])) : ?><a class="phr__link" href="<?php echo esc_url($attributes['linkUrl'] ?: '#'); ?>"><?php echo wp_kses_post($attributes['linkText']); ?></a><?php endif; ?>
			</div>
			<?php if (!empty($attributes['note'])) : ?>
				<p class="phr__note"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/></svg><?php echo wp_kses_post($attributes['note']); ?></p>
			<?php endif; ?>
		</div>
		<?php if ($img_url) : ?>
			<div class="phr__visual">
				<img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" style="object-position:<?php echo esc_attr($img_pos); ?>" fetchpriority="high" />
				<?php if (!empty($attributes['captionTitle']) || !empty($attributes['captionText'])) : ?>
					<div class="phr__caption"><strong><?php echo wp_kses_post($attributes['captionTitle'] ?? ''); ?></strong><?php echo wp_kses_post($attributes['captionText'] ?? ''); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
