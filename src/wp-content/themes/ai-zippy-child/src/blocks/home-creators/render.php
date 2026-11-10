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
if (!function_exists('az_home_tag_icon')) :
	function az_home_tag_icon(string $name): string
	{
		$paths = [
			'crown'     => '<path d="M3 8l4.5 4L12 5l4.5 7L21 8l-2 11H5L3 8z"/>',
			'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M3 13h18"/>',
		];
		return isset($paths[$name]) ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[$name] . '</svg>' : '';
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
				<?php $tag = ($attributes['linkStyle'] ?? 'link') === 'tag'; ?>
				<a class="hcr__link<?php echo $tag ? ' hcr__link--tag' : ''; ?>" href="<?php echo esc_url(az_home_link($attributes['linkUrl'] ?? '')); ?>"><?php if ($tag) { echo az_home_tag_icon((string) ($attributes['linkIcon'] ?? 'none')); // phpcs:ignore WordPress.Security.EscapeOutput
				} ?><?php echo wp_kses_post($attributes['linkText']); ?><?php if (!$tag) : ?> <span aria-hidden="true">↗︎</span><?php endif; ?></a>
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
					<span><?php echo wp_kses_post($c['cta'] ?? ''); ?> <b aria-hidden="true">↗︎</b></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
