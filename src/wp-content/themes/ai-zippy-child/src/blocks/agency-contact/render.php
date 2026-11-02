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
$items       = $attributes['items'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'agc'] + ($anchor ? ['id' => $anchor] : []));
?>
<div <?php echo $wrapper; ?>>
	<div class="agc__container">
		<div class="agc__header reveal-on-scroll reveal-fade-up">
			<h2 class="agc__title"><?php echo wp_kses_post($title); ?></h2>
			<?php if ($description !== '') : ?>
				<p class="agc__desc"><?php echo wp_kses_post($description); ?></p>
			<?php endif; ?>
		</div>

		<div class="agc__grid" data-reveal-stagger=".agc__item" data-reveal-delay-base="100">
			<?php foreach ($items as $item) :
				$url   = $item['url'] ?? '';
				$value = wp_kses_post($item['value'] ?? '');
			?>
				<div class="agc__item reveal-item reveal-fade-up">
					<span class="agc__icon"><?php echo az_child_agency_icon($item['icon'] ?? 'mail'); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<div class="agc__info">
						<span class="agc__label"><?php echo wp_kses_post($item['label'] ?? ''); ?></span>
						<?php if ($url !== '') : ?>
							<a class="agc__value" href="<?php echo esc_url($url); ?>"><?php echo $value; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
						<?php else : ?>
							<span class="agc__value"><?php echo $value; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
