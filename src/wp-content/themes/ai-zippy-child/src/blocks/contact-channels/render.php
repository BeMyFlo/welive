<?php
defined('ABSPATH') || exit;

if (!function_exists('az_contact_badge')) :
function az_contact_badge(string $label): string {
	return '<svg class="cch__badge" viewBox="0 0 24 24" role="img" aria-label="' . esc_attr($label) . '"><path fill="#edbb00" stroke="#edbb00" stroke-width="1.3" stroke-linejoin="round" d="M12 1.8 14.2 3.5 16.9 3.2 18 5.8 20.5 6.9 20.3 9.7 22.1 12 20.3 14.3 20.5 17.1 18 18.2 16.9 20.8 14.2 20.5 12 22.2 9.8 20.5 7.1 20.8 6 18.2 3.5 17.1 3.7 14.3 1.9 12 3.7 9.7 3.5 6.9 6 5.8 7.1 3.2 9.8 3.5Z"/><path fill="none" stroke="#fff" stroke-width="2.6" stroke-linejoin="round" d="m7.5 12.2 3 3 6-6"/></svg>';
}
endif;

$anchor      = $attributes['anchor'] ?? '';
$title       = $attributes['title'] ?? '';
$subtitle    = $attributes['subtitle'] ?? '';
$placeholder = $attributes['placeholderText'] ?? '';
$verified    = $attributes['verifiedLabel'] ?? '';
$items       = $attributes['items'] ?? [];
$tones       = ['featured', 'community', 'plain'];

$wrapper = get_block_wrapper_attributes(['class' => 'cch'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="cch__wrap">
		<div class="cch__heading">
			<h2><?php echo wp_kses_post($title); ?></h2>
			<?php if ($subtitle !== '') : ?>
				<p><?php echo wp_kses_post($subtitle); ?></p>
			<?php endif; ?>
		</div>
		<div class="cch__grid">
			<?php foreach ($items as $item) :
				$tone    = in_array($item['tone'] ?? 'plain', $tones, true) ? $item['tone'] : 'plain';
				$id      = (int) ($item['imageId'] ?? 0);
				$url     = $id ? wp_get_attachment_image_url($id, 'medium_large') : '';
				$url     = $url ?: ($item['imageUrl'] ?? '');
				$name    = trim((string) ($item['title'] ?? ''));
				$cut     = strrpos($name, ' ');
				$head    = $cut === false ? '' : substr($name, 0, $cut + 1);
				$tail    = $cut === false ? $name : substr($name, $cut + 1);
			?>
				<article class="cch__card cch__card--<?php echo esc_attr($tone); ?>">
					<div class="cch__qr">
						<?php if ($url) : ?>
							<img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($item['alt'] ?? ''); ?>" width="520" height="520" loading="lazy" />
						<?php else : ?>
							<span class="cch__wait">
								<svg width="38" height="38" viewBox="0 0 40 40" fill="none" aria-hidden="true"><path d="M15 7H9a2 2 0 0 0-2 2v6m18-8h6a2 2 0 0 1 2 2v6m0 10v6a2 2 0 0 1-2 2h-6m-10 0H9a2 2 0 0 1-2-2v-6" stroke="#7d6e63" stroke-width="2.4" stroke-linecap="round"/></svg>
								<?php echo esc_html($placeholder); ?>
							</span>
						<?php endif; ?>
					</div>
					<div class="cch__copy">
						<h3><?php echo esc_html($head); ?><?php if (!empty($item['verified'])) : ?><span class="cch__nowrap"><?php echo esc_html($tail); ?> <?php echo az_contact_badge($verified); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><?php else : echo esc_html($tail); endif; ?></h3>
						<p><?php echo wp_kses_post($item['text'] ?? ''); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
