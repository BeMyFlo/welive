<?php
defined('ABSPATH') || exit;

$anchor    = $attributes['anchor'] ?? '';
$title     = $attributes['title'] ?? '';
$address   = $attributes['address'] ?? '';
$link_text = $attributes['linkText'] ?? '';
$link_url  = $attributes['linkUrl'] ?? '';
$map_url   = (string) ($attributes['mapUrl'] ?? '');
$map_title = $attributes['mapTitle'] ?? '';
// Only Google Maps embed links are allowed in the iframe.
$map_ok    = (bool) preg_match('#^https://www\.google\.com/maps/embed\?#', $map_url);

$wrapper = get_block_wrapper_attributes(['class' => 'cof'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="cof__wrap">
		<div>
			<h2><?php echo wp_kses_post($title); ?></h2>
			<address><?php echo wp_kses_post($address); ?></address>
		</div>
		<?php if ($link_text !== '') : ?>
			<a href="<?php echo esc_url($link_url ?: '#'); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($link_text); ?></a>
		<?php endif; ?>
		<?php if ($map_ok) : ?>
			<iframe class="cof__map" src="<?php echo esc_url($map_url); ?>" title="<?php echo esc_attr($map_title); ?>" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
		<?php endif; ?>
	</div>
</section>
