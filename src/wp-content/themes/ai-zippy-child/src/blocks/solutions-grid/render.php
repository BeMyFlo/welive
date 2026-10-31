<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$subheading   = $attributes['sectionSubheading'] ?? 'What we do';
$heading      = $attributes['sectionHeading'] ?? 'OUR Solutions';
$desc         = $attributes['sectionDesc'] ?? '';
$bg_image_url = $attributes['bgImageUrl'] ?? '';
$cards        = $attributes['cards'] ?? [];

$bg_class = !empty($bg_image_url) ? 'sg--has-bg' : '';
$wrapper  = get_block_wrapper_attributes([
	'class' => 'sg ' . $bg_class . ' has-text-align-' . $text_align,
	'style' => !empty($bg_image_url) ? 'background-image: url(' . esc_url($bg_image_url) . ');' : ''
]);
?>
<div <?php echo $wrapper; ?>>
	<div class="sg__container">
		<div class="sg__layout">
		<div class="sg__intro reveal-on-scroll reveal-fade-up">
			<div class="sg__subheading-wrap">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="sg__subheading-icon">
					<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#EC4600" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<h6 class="sg__subheading"><?php echo esc_html($subheading); ?></h6>
			</div>
			<h2 class="sg__heading"><?php echo esc_html($heading); ?></h2>
			<p class="sg__desc"><?php echo wp_kses_post(nl2br($desc)); ?></p>
		</div>
			<?php if (!empty($cards)) : ?>
				<div class="sg__cards reveal-on-scroll reveal-fade-up" data-reveal-delay="150">
					<?php foreach ($cards as $card) :
						$card_img  = $card['imageUrl'] ?? '';
						$card_text = $card['text'] ?? '';
					?>
						<figure class="sg__card">
							<div class="sg__card-media">
								<?php if ($card_img) : ?>
									<img src="<?php echo esc_url($card_img); ?>" alt="<?php echo esc_attr(wp_strip_all_tags($card_text)); ?>" loading="lazy" />
								<?php endif; ?>
							</div>
							<?php if ($card_text) : ?>
								<figcaption class="sg__card-text"><?php echo wp_kses_post($card_text); ?></figcaption>
							<?php endif; ?>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
