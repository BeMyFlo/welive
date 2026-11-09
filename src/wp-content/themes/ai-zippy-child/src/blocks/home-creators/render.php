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
if (!function_exists('az_home_link')) :
	function az_home_link(string $url): string
	{
		return $url === '' ? '#' : (str_starts_with($url, '/') ? home_url($url) : $url);
	}
endif;

$creators = $attributes['creators'] ?? [];
$anchor   = $attributes['anchor'] ?? '';
$wrapper  = get_block_wrapper_attributes(['class' => 'hcr'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="hcr__wrap">
		<div class="hcr__top">
			<h2 class="hcr__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?> <span><?php echo wp_kses_post($attributes['titleAccent'] ?? ''); ?></span></h2>
			<?php if (!empty($attributes['linkText'])) : ?>
				<a class="hcr__link" href="<?php echo esc_url(az_home_link($attributes['linkUrl'] ?? '')); ?>"><?php echo wp_kses_post($attributes['linkText']); ?> <span aria-hidden="true">↗</span></a>
			<?php endif; ?>
		</div>
		<div class="hcr__grid">
			<?php foreach ($creators as $c) :
				$src = az_home_img($c, 'large'); ?>
				<a class="hcr__card" href="<?php echo esc_url(az_home_link($c['url'] ?? '')); ?>">
					<div class="hcr__photo">
						<?php if ($src) : ?>
							<img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr(wp_strip_all_tags($c['name'] ?? '')); ?>" loading="lazy" style="object-position:<?php echo esc_attr($c['position'] ?? '50% 50%'); ?>" />
						<?php endif; ?>
					</div>
					<h3><?php echo wp_kses_post($c['name'] ?? ''); ?></h3>
					<span><?php echo wp_kses_post($c['cta'] ?? ''); ?> <b aria-hidden="true">↗</b></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
