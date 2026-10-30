<?php
defined('ABSPATH') || exit;

$title     = $attributes['title'] ?? 'JOIN US';
$btn_text  = $attributes['btnText'] ?? 'GET STARTED WITH WELIVE';
$btn_url   = $attributes['btnUrl'] ?? '#';
$text_align = $attributes['textAlign'] ?? 'center';
$bg_color  = $attributes['backgroundColor'] ?? '#0b0b0b';

$wrapper = get_block_wrapper_attributes([
	'class' => 'hju has-text-align-' . $text_align,
	'style' => 'background-color: ' . esc_attr($bg_color) . ';'
]);
?>
<div <?php echo $wrapper; ?>>
	<div class="hju__container">
		<?php if ($title) : ?>
			<h2 class="hju__title"><?php echo wp_kses_post($title); ?></h2>
		<?php endif; ?>
		
		<?php if ($btn_text) : ?>
			<div class="hju__btn-wrap">
				<a href="<?php echo esc_url($btn_url); ?>" class="hju__btn">
					<?php echo esc_html($btn_text); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
