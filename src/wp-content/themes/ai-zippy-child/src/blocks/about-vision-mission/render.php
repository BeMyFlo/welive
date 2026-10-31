<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$title         = $attributes['title'] ?? 'Vision & Mission';
$vision_text   = $attributes['visionText'] ?? '';
$mission_text  = $attributes['missionText'] ?? '';
$usp_text      = $attributes['uspText'] ?? '';
$graphic_url   = $attributes['graphicImageUrl'] ?? '';
$vision_label  = $attributes['visionLabel'] ?? 'Vision';
$mission_label = $attributes['missionLabel'] ?? 'Mission';
$usp_label     = $attributes['uspLabel'] ?? 'USP';
$cards         = $attributes['cards'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'abvm has-text-align-' . $text_align]);
?>
<div <?php echo $wrapper; ?>>
	<div class="abvm__container">
		<div class="abvm__grid">
			<div class="abvm__content reveal-on-scroll reveal-fade-up">
				<div class="abvm__header">
					<h2 class="abvm__main-title"><?php echo wp_kses_post($title); ?></h2>
				</div>

				<?php if ($vision_text) : ?>
					<div class="abvm__item">
						<span class="abvm__label"><?php echo esc_html($vision_label); ?></span>
						<p class="abvm__text"><?php echo wp_kses_post($vision_text); ?></p>
					</div>
				<?php endif; ?>

				<?php if ($mission_text) : ?>
					<div class="abvm__item">
						<span class="abvm__label"><?php echo esc_html($mission_label); ?></span>
						<p class="abvm__text"><?php echo wp_kses_post($mission_text); ?></p>
					</div>
				<?php endif; ?>

				<?php if ($usp_text) : ?>
					<div class="abvm__item">
						<span class="abvm__label"><?php echo esc_html($usp_label); ?></span>
						<p class="abvm__text"><?php echo wp_kses_post($usp_text); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<?php if (!empty($cards)) : ?>
				<div class="abvm__cards reveal-on-scroll reveal-fade-up" data-reveal-delay="150">
					<?php foreach ($cards as $card) :
						$card_img  = $card['imageUrl'] ?? '';
						$card_text = $card['text'] ?? '';
					?>
						<figure class="abvm__card">
							<div class="abvm__card-media">
								<?php if ($card_img) : ?>
									<img src="<?php echo esc_url($card_img); ?>" alt="<?php echo esc_attr(wp_strip_all_tags($card_text)); ?>" loading="lazy" />
								<?php endif; ?>
							</div>
							<?php if ($card_text) : ?>
								<figcaption class="abvm__card-text"><?php echo wp_kses_post($card_text); ?></figcaption>
							<?php endif; ?>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if (empty($cards) && $graphic_url) : ?>
				<div class="abvm__graphic-wrap reveal-on-scroll reveal-zoom-in" data-reveal-delay="200">
					<img class="abvm__image" src="<?php echo esc_url($graphic_url); ?>" alt="Vision &amp; Mission Graphic" loading="lazy" />
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
