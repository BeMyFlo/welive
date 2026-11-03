<?php
defined('ABSPATH') || exit;

$eyebrow     = $attributes['eyebrow'] ?? '';
$title       = $attributes['title'] ?? '';
$title_line2 = $attributes['titleLine2'] ?? '';
$description = $attributes['description'] ?? '';
$items       = $attributes['items'] ?? [];

$group = 'cfq-' . wp_unique_id(); // makes the accordion exclusive: one answer open at a time

$wrapper = get_block_wrapper_attributes(['class' => 'cfq']);
?>
<div <?php echo $wrapper; ?>>
	<div class="cfq__container">
		<div class="cfq__intro reveal-on-scroll reveal-fade-up">
			<span class="cfq__eyebrow"><?php echo wp_kses_post($eyebrow); ?></span>
			<h2 class="cfq__title"><?php echo wp_kses_post($title); ?><br><?php echo wp_kses_post($title_line2); ?></h2>
			<?php if ($description !== '') : ?>
				<p class="cfq__desc"><?php echo wp_kses_post($description); ?></p>
			<?php endif; ?>
		</div>

		<div class="cfq__list reveal-on-scroll reveal-fade-up" data-reveal-delay="100">
			<?php foreach ($items as $item) : ?>
				<details class="cfq__item" name="<?php echo esc_attr($group); ?>">
					<summary class="cfq__q">
						<span><?php echo wp_kses_post($item['q'] ?? ''); ?></span>
						<span class="cfq__icon" aria-hidden="true"></span>
					</summary>
					<div class="cfq__a"><?php echo wp_kses_post(nl2br($item['a'] ?? '')); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</div>
