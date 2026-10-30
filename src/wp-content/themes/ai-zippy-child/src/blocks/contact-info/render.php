<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$cards   = $attributes['cards'] ?? [];
$wrapper = get_block_wrapper_attributes(['class' => 'ci has-text-align-' . $text_align]);
?>
<div <?php echo $wrapper; ?>>
	<div class="ci__container">
		<div class="ci__flex">
			<?php foreach ($cards as $card) :
				$title   = $card['title'] ?? '';
				$content = $card['content'] ?? '';
				$icon    = $card['icon'] ?? 'email';
			?>
				<div class="ci__card">
					<div class="ci__icon-wrapper">
						<?php if ($icon === 'location') : ?>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EC4600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
								<circle cx="12" cy="10" r="3"></circle>
							</svg>
						<?php elseif ($icon === 'phone') : ?>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EC4600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
							</svg>
						<?php else : ?>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EC4600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
								<polyline points="22,6 12,13 2,6"></polyline>
							</svg>
						<?php endif; ?>
					</div>
					<div class="ci__block">
						<h5 class="ci__card-title"><?php echo esc_html($title); ?></h5>
						<p class="ci__card-content"><?php echo esc_html($content); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
