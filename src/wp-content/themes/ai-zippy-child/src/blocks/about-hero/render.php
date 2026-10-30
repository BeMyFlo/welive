<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$title          = $attributes['title'] ?? 'ABOUT US';
$subtitle       = $attributes['subtitle'] ?? '';
$mobile_bg_text = $attributes['mobileBackgroundText'] ?? "BOLD\nMUSIC\nBOLD\nFUTURE";

$wrapper = get_block_wrapper_attributes(['class' => 'abh has-text-align-' . $text_align]);
?>
<div <?php echo $wrapper; ?>>
	<div class="abh__container">
		<div class="abh__flex">
			<!-- Left Column: Title and Subtitle Description -->
			<div class="abh__left reveal-on-scroll reveal-fade-up">
				<h1 class="abh__title"><?php echo wp_kses_post($title); ?></h1>
				<p class="abh__subtitle"><?php echo wp_kses_post(nl2br($subtitle)); ?></p>
			</div>
			
			<!-- Right Column: Big Stacked Text -->
			<div class="abh__right reveal-on-scroll reveal-fade-up" data-reveal-delay="200">
				<h2 class="abh__big-text"><?php echo wp_kses_post(nl2br($mobile_bg_text)); ?></h2>
			</div>
		</div>
	</div>
</div>
