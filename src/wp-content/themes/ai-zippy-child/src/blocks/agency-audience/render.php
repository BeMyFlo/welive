<?php
defined('ABSPATH') || exit;

$cards   = $attributes['cards'] ?? [];
$tones   = ['dark', 'warm', 'light', 'orange'];
$is_demo = false;
foreach ($cards as $c) {
	if (in_array($c['tone'] ?? '', ['light', 'orange'], true)) {
		$is_demo = true;
	}
}

$wrapper = get_block_wrapper_attributes(['class' => 'aga' . ($is_demo ? ' aga--demo' : '')]);
?>
<div <?php echo $wrapper; ?>>
	<div class="aga__container"<?php echo $is_demo ? '' : ' data-reveal-stagger=".aga__card" data-reveal-delay-base="100"'; ?>>
		<?php foreach ($cards as $card) :
			$tone      = in_array($card['tone'] ?? 'dark', $tones, true) ? $card['tone'] : 'dark';
			$btn_text  = $card['btnText'] ?? '';
			$btn_url   = $card['btnUrl'] ?? '';
			$image_id  = (int) ($card['imageId'] ?? 0);
			$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
			$image_url = $image_url ?: ($card['imageUrl'] ?? '');
			$text      = $card['text'] ?? '';
			$eyebrow   = $card['eyebrow'] ?? '';
		?>
			<div class="aga__card aga__card--<?php echo esc_attr($tone); ?><?php echo $is_demo ? '' : ' reveal-item reveal-fade-up'; ?>">
				<div class="aga__body">
					<?php if ($eyebrow !== '') : ?>
						<p class="aga__eyebrow"><?php echo wp_kses_post($eyebrow); ?></p>
					<?php endif; ?>
					<h2 class="aga__title"><?php echo wp_kses_post($card['title'] ?? ''); ?></h2>
					<?php if ($text !== '') : ?>
						<p class="aga__text"><?php echo wp_kses_post($text); ?></p>
					<?php elseif (($card['subtitle'] ?? '') !== '') : ?>
						<p class="aga__subtitle"><?php echo wp_kses_post($card['subtitle']); ?></p>
					<?php endif; ?>
					<?php if ($btn_text !== '') : ?>
						<a class="aga__btn" href="<?php echo esc_url($btn_url ?: '#'); ?>">
							<span><?php echo esc_html($btn_text); ?></span>
							<?php if (!$is_demo) : ?>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
							<?php endif; ?>
						</a>
					<?php endif; ?>
				</div>
				<?php if (!$is_demo || $image_url) : ?>
					<div class="aga__media">
						<?php if ($image_url) : ?>
							<img src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy" />
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
