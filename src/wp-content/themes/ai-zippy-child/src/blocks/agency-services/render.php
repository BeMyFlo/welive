<?php
defined('ABSPATH') || exit;

if (!function_exists('az_child_agency_icon')) :
function az_child_agency_icon(string $name): string {
	$paths = [
		'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
		'users'  => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
		'chart'  => '<path d="M5 21V12M12 21V5M19 21V9"/>',
		'mail'   => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'phone'  => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'pin'    => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
	];
	return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? $paths['search']) . '</svg>';
}
endif;

$anchor      = $attributes['anchor'] ?? '';
$title       = $attributes['title'] ?? '';
$description = $attributes['description'] ?? '';
$cards       = $attributes['cards'] ?? [];
$btn_text    = $attributes['btnText'] ?? '';
$btn_url     = $attributes['btnUrl'] ?? '';

$is_steps = ($attributes['layout'] ?? 'cards') === 'steps';

$wrapper = get_block_wrapper_attributes(['class' => 'ags' . ($is_steps ? ' ags--steps' : '')] + ($anchor ? ['id' => $anchor] : []));

if ($is_steps) :
	?>
	<section <?php echo $wrapper; ?>>
		<div class="ags__s-wrap">
			<div class="ags__s-intro">
				<h2 class="ags__s-title"><?php echo wp_kses_post($title); ?></h2>
				<?php if ($description !== '') : ?>
					<p class="ags__s-desc"><?php echo wp_kses_post($description); ?></p>
				<?php endif; ?>
			</div>
			<div class="ags__s-steps">
				<?php foreach ($cards as $card) : ?>
					<article class="ags__s-step">
						<h3><?php echo wp_kses_post($card['title'] ?? ''); ?></h3>
						<p><?php echo wp_kses_post($card['text'] ?? ''); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if ($btn_text !== '') : ?>
				<a class="ags__s-btn" href="<?php echo esc_url($btn_url ?: '#'); ?>"><?php echo esc_html($btn_text); ?></a>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return;
endif;
?>
<div <?php echo $wrapper; ?>>
	<div class="ags__container">
		<div class="ags__header reveal-on-scroll reveal-fade-up">
			<h2 class="ags__title"><?php echo wp_kses_post($title); ?></h2>
			<?php if ($description !== '') : ?>
				<p class="ags__desc"><?php echo wp_kses_post($description); ?></p>
			<?php endif; ?>
		</div>

		<div class="ags__grid" data-reveal-stagger=".ags__card" data-reveal-delay-base="100">
			<?php foreach ($cards as $card) :
				$image_url = $card['imageUrl'] ?? '';
			?>
				<div class="ags__card reveal-item reveal-fade-up">
					<span class="ags__icon"><?php echo az_child_agency_icon($card['icon'] ?? 'search'); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="ags__card-title"><?php echo wp_kses_post($card['title'] ?? ''); ?></h3>
					<p class="ags__card-subtitle"><?php echo wp_kses_post($card['subtitle'] ?? ''); ?></p>
					<p class="ags__card-text"><?php echo wp_kses_post($card['text'] ?? ''); ?></p>
					<div class="ags__media">
						<?php if ($image_url) : ?>
							<img src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy" />
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ($btn_text !== '') : ?>
			<div class="ags__cta">
				<a class="ags__btn" href="<?php echo esc_url($btn_url ?: '#'); ?>"><?php echo esc_html($btn_text); ?></a>
			</div>
		<?php endif; ?>
	</div>
</div>
