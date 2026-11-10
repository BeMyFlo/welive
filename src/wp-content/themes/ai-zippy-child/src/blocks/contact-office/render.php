<?php
defined('ABSPATH') || exit;

$anchor    = $attributes['anchor'] ?? '';
$title     = $attributes['title'] ?? '';
$address   = $attributes['address'] ?? '';
$link_text = $attributes['linkText'] ?? '';
$link_url  = $attributes['linkUrl'] ?? '';

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
	</div>
</section>
