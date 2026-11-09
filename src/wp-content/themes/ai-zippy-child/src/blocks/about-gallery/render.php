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
$filters  = $attributes['filters'] ?? [];
$per_page = max(1, (int) ($attributes['perPage'] ?? 6));
$anchor   = $attributes['anchor'] ?? '';
$unit     = $attributes['unit'] ?? '';
$wrapper  = get_block_wrapper_attributes([
	'class'         => 'agl',
	'data-per-page' => (string) $per_page,
	'data-unit'     => $unit,
] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="agl__wrap">
		<div class="agl__heading">
			<div>
				<?php if (!empty($attributes['eyebrow'])) : ?><p class="agl__eyebrow"><span aria-hidden="true">↗︎</span> <?php echo wp_kses_post($attributes['eyebrow']); ?></p><?php endif; ?>
				<h2 class="agl__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?> <span><?php echo wp_kses_post($attributes['titleAccent'] ?? ''); ?></span></h2>
			</div>
			<div class="agl__aside">
				<?php if (!empty($attributes['intro'])) : ?><p><?php echo wp_kses_post($attributes['intro']); ?></p><?php endif; ?>
				<?php if ($filters) : ?>
					<div class="agl__filters" role="group" aria-label="<?php echo esc_attr__('Lọc ảnh', 'ai-zippy-child'); ?>">
						<button type="button" aria-pressed="true" data-filter="all"><?php echo esc_html($attributes['allLabel'] ?? 'Tất cả'); ?></button>
						<?php foreach ($filters as $f) : ?>
							<button type="button" aria-pressed="false" data-filter="<?php echo esc_attr($f['key'] ?? ''); ?>"><?php echo esc_html($f['label'] ?? ''); ?></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="agl__grid">
			<?php foreach ($items as $it) :
				$thumb = az_home_img($it, 'large');
				$full  = az_home_img($it, 'full');
				if (!$thumb) {
					continue;
				}
				$title = wp_strip_all_tags($it['title'] ?? ''); ?>
				<article class="agl__card" data-cat="<?php echo esc_attr($it['category'] ?? ''); ?>">
					<button type="button" data-full="<?php echo esc_url($full); ?>" data-title="<?php echo esc_attr($title); ?>" data-desc="<?php echo esc_attr(wp_strip_all_tags($it['description'] ?? '')); ?>" aria-label="<?php echo esc_attr($title); ?>">
						<span class="agl__image">
							<img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" />
							<span class="agl__expand" aria-hidden="true">↗︎</span>
						</span>
						<span class="agl__caption"><span class="agl__name"><?php echo esc_html($title); ?></span><small><?php echo esc_html(wp_strip_all_tags($it['label'] ?? '')); ?></small></span>
					</button>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="agl__bottom">
			<p class="agl__status" role="status"></p>
			<?php if (!empty($attributes['moreText'])) : ?>
				<button type="button" class="agl__more"><?php echo wp_kses_post($attributes['moreText']); ?> <span aria-hidden="true">+</span></button>
			<?php endif; ?>
		</div>
	</div>

	<dialog class="agl__dialog" aria-label="<?php echo esc_attr__('Xem ảnh', 'ai-zippy-child'); ?>">
		<div class="agl__lb-bar"><p class="agl__lb-count"></p><button type="button" class="agl__lb-close" aria-label="Đóng ảnh">Đóng <span aria-hidden="true">×</span></button></div>
		<div class="agl__lb-stage">
			<button type="button" class="agl__lb-prev" aria-label="Ảnh trước">←</button>
			<img class="agl__lb-img" alt="" />
			<button type="button" class="agl__lb-next" aria-label="Ảnh tiếp theo">→</button>
		</div>
		<div class="agl__lb-caption"><h3 class="agl__lb-title"></h3><p class="agl__lb-desc"></p></div>
	</dialog>
</section>
