<?php
defined('ABSPATH') || exit;

$anchor  = $attributes['anchor'] ?? '';
$image   = is_array($attributes['image'] ?? null) ? $attributes['image'] : [];
$img_url = !empty($image['imageId']) ? wp_get_attachment_image_url((int) $image['imageId'], 'full') : '';
$img_url = $img_url ?: ($image['imageUrl'] ?? '');
$img_alt = (string) ($image['alt'] ?? '');
$img_pos = preg_match('/^[0-9.%\s a-z-]{1,30}$/i', (string) ($image['position'] ?? '')) ? $image['position'] : '54% 48%';
$title   = $attributes['title'] ?? '';
$accent  = $attributes['titleAccent'] ?? '';
$btn     = $attributes['btnText'] ?? '';
$btn_url = $attributes['btnUrl'] ?? '';

$wrapper = get_block_wrapper_attributes(['class' => 'cbn'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<?php if ($img_url) : ?>
		<img class="cbn__image" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" style="object-position:<?php echo esc_attr($img_pos); ?>" fetchpriority="high" />
	<?php endif; ?>
	<div class="cbn__wrap">
		<h1 class="cbn__title"><?php echo wp_kses_post($title); ?><?php if ($accent !== '') : ?> <span><?php echo wp_kses_post($accent); ?></span><?php endif; ?></h1>
		<?php if ($btn !== '') : ?>
			<a class="cbn__btn" href="<?php echo esc_url($btn_url ?: '#'); ?>"><?php echo esc_html($btn); ?></a>
		<?php endif; ?>
	</div>
</section>
