<?php
defined('ABSPATH') || exit;

$title     = $attributes['title'] ?? 'JOIN US';
$btn_text  = $attributes['btnText'] ?? 'GET STARTED WITH WELIVE';
$btn_url   = $attributes['btnUrl'] ?? '#';
$text_align = $attributes['textAlign'] ?? 'center';
$bg_color  = $attributes['backgroundColor'] ?? '#0b0b0b';
$variant   = $attributes['variant'] ?? 'classic';
$kicker    = $attributes['kicker'] ?? '';

$wrapper = get_block_wrapper_attributes([
	'class' => 'hju hju--' . $variant . ' has-text-align-' . $text_align,
	'style' => 'background-color: ' . esc_attr($bg_color) . ';'
]);
?>
<div <?php echo $wrapper; ?>>
	<div class="hju__container">
		<?php if ($title || $kicker) : ?>
			<div class="hju__heading">
				<?php if ($kicker) : ?><p class="hju__kicker"><?php echo wp_kses_post($kicker); ?></p><?php endif; ?>
				<?php if ($title) : ?><h2 class="hju__title"><?php echo wp_kses_post($title); ?></h2><?php endif; ?>
			</div>
		<?php endif; ?>
		
		<?php if ($btn_text) : ?>
			<div class="hju__btn-wrap">
				<a href="<?php echo esc_url(str_starts_with($btn_url, '/') ? home_url($btn_url) : $btn_url); ?>" class="hju__btn">
					<?php echo esc_html($btn_text); ?><?php echo $variant === 'band' ? ' <span aria-hidden="true">↗</span>' : ''; ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
