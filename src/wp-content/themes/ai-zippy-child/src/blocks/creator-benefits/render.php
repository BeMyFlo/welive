<?php
defined('ABSPATH') || exit;

$anchor      = $attributes['anchor'] ?? '';
$eyebrow     = $attributes['eyebrow'] ?? '';
$title       = $attributes['title'] ?? '';
$title_line2 = $attributes['titleLine2'] ?? '';
$description = $attributes['description'] ?? '';
$cards       = $attributes['cards'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'cbn'] + ($anchor ? ['id' => $anchor] : []));
?>
<div <?php echo $wrapper; ?>>
	<div class="cbn__container">
		<div class="cbn__header reveal-on-scroll reveal-fade-up">
			<div class="cbn__intro">
				<?php if ($eyebrow !== '') : ?>
					<span class="cbn__eyebrow"><?php echo wp_kses_post($eyebrow); ?></span>
				<?php endif; ?>
				<h2 class="cbn__title"><?php echo wp_kses_post($title); ?><br><?php echo wp_kses_post($title_line2); ?></h2>
			</div>
			<?php if ($description !== '') : ?>
				<p class="cbn__desc"><?php echo wp_kses_post($description); ?></p>
			<?php endif; ?>
		</div>

		<div class="cbn__grid" data-reveal-stagger=".cbn__card" data-reveal-delay-base="100">
			<?php foreach ($cards as $card) :
				$image_url = $card['imageUrl'] ?? '';
				$bullets   = array_filter((array) ($card['bullets'] ?? []), 'strlen');
			?>
				<div class="cbn__card reveal-item reveal-fade-up">
					<div class="cbn__media">
						<?php if ($image_url) : ?>
							<img src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy" />
						<?php endif; ?>
					</div>
					<div class="cbn__body">
						<h3 class="cbn__card-title"><?php echo wp_kses_post($card['title'] ?? ''); ?></h3>
						<?php if ($bullets) : ?>
							<ul class="cbn__list">
								<?php foreach ($bullets as $bullet) : ?>
									<li><?php echo wp_kses_post($bullet); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
