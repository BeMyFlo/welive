<?php
defined('ABSPATH') || exit;

$cards   = $attributes['cards'] ?? [];
$wrapper = get_block_wrapper_attributes(['class' => 'aga']);
?>
<div <?php echo $wrapper; ?>>
	<div class="aga__container" data-reveal-stagger=".aga__card" data-reveal-delay-base="100">
		<?php foreach ($cards as $card) :
			$tone      = ($card['tone'] ?? 'dark') === 'warm' ? 'warm' : 'dark';
			$btn_text  = $card['btnText'] ?? '';
			$btn_url   = $card['btnUrl'] ?? '';
			$image_url = $card['imageUrl'] ?? '';
		?>
			<div class="aga__card aga__card--<?php echo esc_attr($tone); ?> reveal-item reveal-fade-up">
				<div class="aga__body">
					<h2 class="aga__title"><?php echo wp_kses_post($card['title'] ?? ''); ?></h2>
					<p class="aga__subtitle"><?php echo wp_kses_post($card['subtitle'] ?? ''); ?></p>
					<?php if ($btn_text !== '') : ?>
						<a class="aga__btn" href="<?php echo esc_url($btn_url ?: '#'); ?>">
							<span><?php echo esc_html($btn_text); ?></span>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</a>
					<?php endif; ?>
				</div>
				<div class="aga__media">
					<?php if ($image_url) : ?>
						<img src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy" />
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
