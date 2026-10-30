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

			<div class="abvm__graphic-wrap reveal-on-scroll reveal-zoom-in" data-reveal-delay="200">
				<?php if ($graphic_url) : ?>
					<img class="abvm__image" src="<?php echo esc_url($graphic_url); ?>" alt="Vision &amp; Mission Graphic" loading="lazy" />
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
