<?php

defined('ABSPATH') || exit;

$ticker_text = $attributes['tickerText'] ?? 'Pulse Forward';
$speed       = $attributes['speed'] ?? 12;
$text_color  = $attributes['textColor'] ?? '#ffffff';
$bg_color    = $attributes['bgColor'] ?? '#EC4600';
$logo_url    = $attributes['logoUrl'] ?? '';
$logo_height = $attributes['logoHeight'] ?? 60;

$wrapper = get_block_wrapper_attributes(['class' => 'st']);
?>
<div <?php echo $wrapper; ?> style="background-color: <?php echo esc_attr($bg_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
	<div class="st__wrap">
		<div class="st__track" style="animation-duration: <?php echo esc_attr($speed); ?>s;">
			<div class="st__content">
				<?php for ($i = 0; $i < 4; $i++) : ?>
					<?php if (!empty($logo_url)) : ?>
						<img class="st__logo" src="<?php echo esc_url($logo_url); ?>" style="height: <?php echo esc_attr($logo_height); ?>px; width: auto;" alt="Logo" />
					<?php else : ?>
						<span class="st__text"><?php echo esc_html($ticker_text); ?></span>
					<?php endif; ?>
					<span class="st__dot"></span>
				<?php endfor; ?>
			</div>
			<div class="st__content">
				<?php for ($i = 0; $i < 4; $i++) : ?>
					<?php if (!empty($logo_url)) : ?>
						<img class="st__logo" src="<?php echo esc_url($logo_url); ?>" style="height: <?php echo esc_attr($logo_height); ?>px; width: auto;" alt="Logo" />
					<?php else : ?>
						<span class="st__text"><?php echo esc_html($ticker_text); ?></span>
					<?php endif; ?>
					<span class="st__dot"></span>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</div>
