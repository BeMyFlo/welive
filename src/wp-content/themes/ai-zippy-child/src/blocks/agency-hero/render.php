<?php
defined('ABSPATH') || exit;

$eyebrow     = $attributes['eyebrow'] ?? '';
$title       = $attributes['title'] ?? '';
$description = $attributes['description'] ?? '';
$images      = $attributes['images'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'agh']);
?>
<div <?php echo $wrapper; ?>>
	<div class="agh__container">
		<div class="agh__text reveal-on-scroll reveal-fade-up">
			<?php if ($eyebrow !== '') : ?>
				<span class="agh__eyebrow"><?php echo wp_kses_post($eyebrow); ?></span>
			<?php endif; ?>
			<h1 class="agh__title"><?php echo wp_kses_post($title); ?></h1>
			<?php if ($description !== '') : ?>
				<p class="agh__desc"><?php echo wp_kses_post($description); ?></p>
			<?php endif; ?>
		</div>

		<div class="agh__collage reveal-on-scroll reveal-fade-up" data-reveal-delay="150">
			<?php foreach (array_slice($images, 0, 4) as $i => $img) : ?>
				<div class="agh__img agh__img--<?php echo (int) $i + 1; ?>">
					<?php if (!empty($img['imageUrl'])) : ?>
						<img src="<?php echo esc_url($img['imageUrl']); ?>" alt="" loading="lazy" />
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
