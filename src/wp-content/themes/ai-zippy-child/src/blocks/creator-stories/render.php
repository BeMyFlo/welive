<?php
defined('ABSPATH') || exit;

if (!function_exists('az_home_img')) :
	function az_home_img(array $item, string $size = 'full'): string
	{
		$id = (int) ($item['imageId'] ?? 0);
		if ($id) {
			$url = wp_get_attachment_image_url($id, $size);
			if ($url) {
				return $url;
			}
		}
		return (string) ($item['imageUrl'] ?? '');
	}
endif;

$items    = $attributes['items'] ?? [];
$ph_count = max(0, (int) ($attributes['pendingCount'] ?? 0));
$row_id   = 'cst-' . wp_unique_id();
$anchor   = $attributes['anchor'] ?? '';
$is_last  = $attributes['isLast'] ?? true;
$eyebrow  = $attributes['eyebrow'] ?? '';
$wrapper  = get_block_wrapper_attributes([
	'class' => 'cst' . ($eyebrow !== '' ? ' cst--first' : '') . ($is_last ? ' cst--last' : ''),
] + ($anchor && $eyebrow !== '' ? ['id' => $anchor] : []));
$initials = static function (string $name): string {
	$out = '';
	foreach (array_slice(preg_split('/\s+/u', trim(wp_strip_all_tags($name))) ?: [], 0, 2) as $w) {
		$out .= mb_strtoupper(mb_substr($w, 0, 1));
	}
	return $out;
};
?>
<section <?php echo $wrapper; ?>>
	<div class="cst__wrap">
		<?php if ($eyebrow !== '') : ?><h2 class="cst__eyebrow"><?php echo wp_kses_post($eyebrow); ?></h2><?php endif; ?>
		<div class="cst__row-heading">
			<h3 id="<?php echo esc_attr($row_id); ?>-title"><?php echo wp_kses_post($attributes['rowTitle'] ?? ''); ?></h3>
			<div class="cst__controls" role="group" aria-label="<?php echo esc_attr(wp_strip_all_tags($attributes['rowTitle'] ?? '')); ?>">
				<button type="button" class="cst__prev" aria-controls="<?php echo esc_attr($row_id); ?>" aria-label="Cuộn sang trái" disabled><?php echo esc_html($attributes['prevText'] ?? ''); ?></button>
				<button type="button" class="cst__next" aria-controls="<?php echo esc_attr($row_id); ?>" aria-label="Cuộn sang phải"><?php echo esc_html($attributes['nextText'] ?? ''); ?></button>
			</div>
		</div>
		<div class="cst__track" id="<?php echo esc_attr($row_id); ?>" tabindex="0" role="region" aria-labelledby="<?php echo esc_attr($row_id); ?>-title">
			<?php foreach ($items as $it) :
				$src   = az_home_img($it, 'large');
				$name  = wp_strip_all_tags($it['name'] ?? '');
				$links = [];
				foreach (['tiktok' => 'TikTok', 'facebook' => 'Facebook', 'instagram' => 'Instagram'] as $k => $label) {
					if (!empty($it[$k])) {
						$links[] = ['label' => $label, 'url' => $it[$k]];
					}
				} ?>
				<article class="cst__card">
					<button type="button" data-name="<?php echo esc_attr($name); ?>" data-image="<?php echo esc_url(az_home_img($it, 'full')); ?>" data-position="<?php echo esc_attr($it['position'] ?? '50% 40%'); ?>" data-initials="<?php echo esc_attr($initials($name)); ?>" data-bio="<?php echo esc_attr(wp_strip_all_tags($it['bio'] ?? '')); ?>" data-links="<?php echo esc_attr(wp_json_encode($links)); ?>" aria-label="<?php echo esc_attr($name); ?>">
						<?php if ($src) : ?><img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($name); ?>" style="object-position:<?php echo esc_attr($it['position'] ?? '50% 40%'); ?>" loading="lazy" /><?php endif; ?>
						<span class="cst__overlay"><span class="cst__name"><?php echo esc_html($name); ?></span><span class="cst__link"><?php echo wp_kses_post($it['linkText'] ?? ''); ?></span></span>
					</button>
				</article>
			<?php endforeach; ?>
			<?php for ($i = 0; $i < $ph_count; $i++) : ?>
				<article class="cst__card cst__card--pending">
					<span class="cst__mark" aria-hidden="true">W.</span>
					<div><h4><?php echo wp_kses_post($attributes['pendingTitle'] ?? ''); ?></h4><p><?php echo wp_kses_post($attributes['pendingText'] ?? ''); ?></p></div>
				</article>
			<?php endfor; ?>
		</div>
	</div>

	<dialog class="cst__dialog" data-eyebrow="<?php echo esc_attr(mb_strtoupper(wp_strip_all_tags($attributes['rowTitle'] ?? ''))); ?>" data-note="<?php echo esc_attr($attributes['previewNote'] ?? ''); ?>" data-fallback="<?php echo esc_attr($attributes['dialogFallback'] ?? ''); ?>" aria-label="<?php echo esc_attr__('Câu chuyện', 'ai-zippy-child'); ?>">
		<button type="button" class="cst__close" aria-label="Đóng">×</button>
		<div class="cst__visual"></div>
		<div class="cst__content">
			<p class="cst__eyebrow-d"></p>
			<h2 class="cst__dname"></h2>
			<p class="cst__bio"></p>
			<p class="cst__note"></p>
			<div class="cst__links"></div>
		</div>
	</dialog>
</section>
