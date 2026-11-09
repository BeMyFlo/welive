<?php
defined('ABSPATH') || exit;

$items   = $attributes['items'] ?? [];
$anchor  = $attributes['anchor'] ?? '';
$wrapper = get_block_wrapper_attributes(['class' => 'ahy'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="ahy__wrap">
		<?php if (!empty($attributes['eyebrow'])) : ?><p class="ahy__eyebrow"><?php echo wp_kses_post($attributes['eyebrow']); ?></p><?php endif; ?>
		<h2 class="ahy__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?></h2>
		<ol class="ahy__list">
			<?php foreach ($items as $it) : ?>
				<li><span class="ahy__year"><?php echo wp_kses_post($it['year'] ?? ''); ?></span><p><?php echo wp_kses_post($it['text'] ?? ''); ?></p></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
