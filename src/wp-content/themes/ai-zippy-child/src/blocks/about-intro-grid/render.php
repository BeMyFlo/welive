<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$intro_text = $attributes['introText'] ?? '';
$col1_text  = $attributes['col1Text'] ?? '';
$col2_text  = $attributes['col2Text'] ?? '';
$images     = $attributes['images'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'abig has-text-align-' . $text_align]);

// Group images into 3 columns for a clean staggered masonry layout
$columns = [
	[$images[0] ?? null, $images[1] ?? null],
	[$images[2] ?? null, $images[3] ?? null],
	[$images[4] ?? null, $images[5] ?? null],
];
?>
<div <?php echo $wrapper; ?>>
	<div class="abig__container">
		<div class="abig__content reveal-on-scroll reveal-fade-up">
			<div class="abig__intro-wrap">
				<h2 class="abig__intro"><?php echo wp_kses_post($intro_text); ?></h2>
			</div>
			
			<div class="abig__grid-text">
				<p class="abig__para"><?php echo wp_kses_post(nl2br($col1_text)); ?></p>
				<p class="abig__para"><?php echo wp_kses_post(nl2br($col2_text)); ?></p>
			</div>
		</div>

		<!-- Staggered Masonry Grid -->
		<div class="abig__collage" data-reveal-stagger=".abig__collage-box" data-reveal-delay-base="150">
			<?php foreach ($columns as $col_index => $col_images) : ?>
				<div class="abig__collage-column abig__collage-column--<?php echo $col_index + 1; ?>">
					<?php foreach ($col_images as $image) : 
						$url = $image['imageUrl'] ?? '';
					?>
						<?php if ($url) : 
							$img_title    = $image['title'] ?? '';
							$img_subtitle = $image['subtitle'] ?? '';
						?>
							<div class="abig__collage-box reveal-item reveal-zoom-in">
								<img class="abig__collage-img" src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($img_title); ?>" loading="lazy" />
								<?php if (!empty($img_title) || !empty($img_subtitle)) : ?>
									<div class="abig__info-overlay">
										<div class="abig__info-content">
											<?php if (!empty($img_title)) : ?>
												<h3 class="abig__info-title"><?php echo wp_kses_post($img_title); ?></h3>
											<?php endif; ?>
											<?php if (!empty($img_subtitle)) : ?>
												<p class="abig__info-subtitle"><?php echo wp_kses_post($img_subtitle); ?></p>
											<?php endif; ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						<?php else : ?>
							<!-- Beautiful abstract placeholder card if no image uploaded -->
							<div class="abig__collage-box abig__collage-box--placeholder reveal-item reveal-zoom-in">
								<div class="abig__placeholder-pattern"></div>
								<span class="abig__placeholder-text">+ Upload Image</span>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
