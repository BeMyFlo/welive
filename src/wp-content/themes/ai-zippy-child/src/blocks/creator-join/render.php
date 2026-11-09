<?php
defined('ABSPATH') || exit;

$btn_url  = $attributes['btnUrl'] ?? '';
$btn_url  = $btn_url === '' ? '#' : (str_starts_with($btn_url, '/') ? home_url($btn_url) : $btn_url);
$external = !str_starts_with($btn_url, home_url());
$anchor   = $attributes['anchor'] ?? '';
$wrapper  = get_block_wrapper_attributes(['class' => 'cjn'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="cjn__wrap">
		<div class="cjn__copy">
			<?php if (!empty($attributes['eyebrow'])) : ?><p class="cjn__eyebrow"><?php echo wp_kses_post($attributes['eyebrow']); ?></p><?php endif; ?>
			<h2 class="cjn__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?></h2>
			<?php if (!empty($attributes['text'])) : ?><p class="cjn__text"><?php echo wp_kses_post($attributes['text']); ?></p><?php endif; ?>
			<?php if (!empty($attributes['btnText'])) : ?>
				<a class="cjn__button" href="<?php echo esc_url($btn_url); ?>"<?php echo $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo wp_kses_post($attributes['btnText']); ?> <span aria-hidden="true">↗︎</span></a>
			<?php endif; ?>
		</div>
		<div class="cjn__art" aria-hidden="true">
			<span class="cjn__art-text"><?php echo wp_kses_post($attributes['artText'] ?? ''); ?></span>
			<span class="cjn__arrow">↗︎</span>
		</div>
	</div>
</section>
