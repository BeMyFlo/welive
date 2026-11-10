<?php
defined('ABSPATH') || exit;

$anchor  = $attributes['anchor'] ?? '';
$title   = $attributes['title'] ?? '';
$text    = $attributes['text'] ?? '';
$btn     = $attributes['btnText'] ?? '';
$btn_url = $attributes['btnUrl'] ?? '';
$new_tab = !empty($attributes['newTab']);

$wrapper = get_block_wrapper_attributes(['class' => 'agc'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="agc__wrap">
		<div class="agc__copy">
			<h2 class="agc__title"><?php echo wp_kses_post($title); ?></h2>
			<?php if ($text !== '') : ?>
				<p><?php echo wp_kses_post($text); ?></p>
			<?php endif; ?>
		</div>
		<?php if ($btn !== '') : ?>
			<a class="agc__btn" href="<?php echo esc_url($btn_url ?: '#'); ?>"<?php echo $new_tab ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html($btn); ?></a>
		<?php endif; ?>
	</div>
</section>
