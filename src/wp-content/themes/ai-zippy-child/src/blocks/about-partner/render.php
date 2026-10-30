<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$subheading  = $attributes['subheading'] ?? 'Who we are';
$heading     = $attributes['heading'] ?? 'Your <span class="ap__highlight">All-in-One</span> Music Partner';
$description = $attributes['description'] ?? '';
$button_text = $attributes['buttonText'] ?? 'LEARN MORE';
$button_url  = $attributes['buttonUrl'] ?? '#';
$banner_url  = $attributes['bannerImageUrl'] ?? '';

$wrapper = get_block_wrapper_attributes(['class' => 'ap has-text-align-' . $text_align]);
?>
<div <?php echo $wrapper; ?>>
	<div class="ap__container">
		<div class="ap__grid reveal-on-scroll reveal-fade-up">
			<!-- Column Left -->
			<div class="ap__col ap__col--left">
				<div class="ap__subheading-wrap">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ap__subheading-icon">
						<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#EC4600" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<h6 class="ap__subheading"><?php echo esc_html($subheading); ?></h6>
				</div>
				<h2 class="ap__heading"><?php echo wp_kses_post($heading); ?></h2>
			</div>

			<!-- Column Right -->
			<div class="ap__col ap__col--right">
				<p class="ap__desc"><?php echo wp_kses_post(nl2br($description)); ?></p>
				
				<?php if ($button_text && $button_url) : ?>
					<a href="<?php echo esc_url($button_url); ?>" class="ap__btn">
						<?php echo esc_html($button_text); ?>
						<span class="ap__btn-circle">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ap__btn-icon">
								<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<!-- Banner Image -->
		<?php if ($banner_url) : ?>
			<div class="ap__banner reveal-on-scroll reveal-zoom-in" data-reveal-delay="200">
				<img class="ap__banner-img" src="<?php echo esc_url($banner_url); ?>" alt="" loading="lazy" />
			</div>
		<?php endif; ?>
	</div>
</div>
