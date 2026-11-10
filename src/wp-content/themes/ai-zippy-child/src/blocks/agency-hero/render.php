<?php
defined('ABSPATH') || exit;

$eyebrow     = $attributes['eyebrow'] ?? '';
$title       = $attributes['title'] ?? '';
$description = $attributes['description'] ?? '';
$images      = $attributes['images'] ?? [];
$layout      = ($attributes['layout'] ?? 'collage') === 'split' ? 'split' : 'collage';

if ($layout === 'split') :
	$accent   = $attributes['titleAccent'] ?? '';
	$image    = is_array($attributes['image'] ?? null) ? $attributes['image'] : [];
	$img_url  = !empty($image['imageId']) ? wp_get_attachment_image_url((int) $image['imageId'], 'full') : '';
	$img_url  = $img_url ?: ($image['imageUrl'] ?? '');
	$img_alt  = (string) ($image['alt'] ?? '');
	$img_pos  = preg_match('/^[0-9.%\s a-z-]{1,30}$/i', (string) ($image['position'] ?? '')) ? $image['position'] : '50% 55%';
	$wrapper  = get_block_wrapper_attributes(['class' => 'agh agh--split']);
	?>
	<section <?php echo $wrapper; ?>>
		<div class="agh__s-wrap">
			<div class="agh__s-copy">
				<?php if ($eyebrow !== '') : ?>
					<p class="agh__s-eyebrow"><?php echo wp_kses_post($eyebrow); ?></p>
				<?php endif; ?>
				<h1 class="agh__s-title"><?php echo wp_kses_post($title); ?><?php if ($accent !== '') : ?> <span><?php echo wp_kses_post($accent); ?></span><?php endif; ?></h1>
				<?php if ($description !== '') : ?>
					<p class="agh__s-desc"><?php echo wp_kses_post($description); ?></p>
				<?php endif; ?>
			</div>
			<?php if ($img_url) : ?>
				<img class="agh__s-photo" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" style="object-position:<?php echo esc_attr($img_pos); ?>" fetchpriority="high" />
			<?php endif; ?>
		</div>
	</section>
	<?php
	return;
endif;

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
