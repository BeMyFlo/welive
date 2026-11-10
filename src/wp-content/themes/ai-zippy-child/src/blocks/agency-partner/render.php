<?php
defined('ABSPATH') || exit;

$anchor  = $attributes['anchor'] ?? '';
$image   = is_array($attributes['image'] ?? null) ? $attributes['image'] : [];
$img_url = !empty($image['imageId']) ? wp_get_attachment_image_url((int) $image['imageId'], 'large') : '';
$img_url = $img_url ?: ($image['imageUrl'] ?? '');
$img_alt = (string) ($image['alt'] ?? '');
$img_pos = preg_match('/^[0-9.%\s a-z-]{1,30}$/i', (string) ($image['position'] ?? '')) ? $image['position'] : '50% 50%';
$title   = $attributes['title'] ?? '';
$text    = $attributes['text'] ?? '';
$text2   = $attributes['text2'] ?? '';
$btn     = $attributes['btnText'] ?? '';
$btn_url = $attributes['btnUrl'] ?? '';

$wrapper = get_block_wrapper_attributes(['class' => 'agp'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="agp__wrap">
		<?php if ($img_url) : ?>
			<img class="agp__photo" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" style="object-position:<?php echo esc_attr($img_pos); ?>" loading="lazy" />
		<?php endif; ?>
		<div class="agp__copy">
			<h2 class="agp__title"><?php echo wp_kses_post($title); ?></h2>
			<?php if ($text !== '') : ?>
				<p><?php echo wp_kses_post($text); ?></p>
			<?php endif; ?>
			<?php if ($text2 !== '') : ?>
				<p><?php echo wp_kses_post($text2); ?></p>
			<?php endif; ?>
			<?php if ($btn !== '') : ?>
				<a class="agp__btn" href="<?php echo esc_url($btn_url ?: '#'); ?>"><?php echo esc_html($btn); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
