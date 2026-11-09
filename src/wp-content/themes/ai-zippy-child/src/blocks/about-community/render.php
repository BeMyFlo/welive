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

$src      = az_home_img($attributes, 'large');
$btn_url  = az_home_link($attributes['btnUrl'] ?? '');
$external = !str_starts_with($btn_url, home_url());
$anchor   = $attributes['anchor'] ?? '';
$wrapper  = get_block_wrapper_attributes(['class' => 'acm'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="acm__wrap">
		<div class="acm__photo">
			<?php if ($src) : ?><img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($attributes['alt'] ?? ''); ?>" loading="lazy" /><?php endif; ?>
			<?php if (!empty($attributes['caption'])) : ?><span class="acm__caption"><?php echo wp_kses_post($attributes['caption']); ?> <span aria-hidden="true">↗︎</span></span><?php endif; ?>
		</div>
		<div class="acm__copy">
			<?php if (!empty($attributes['eyebrow'])) : ?><p class="acm__eyebrow"><?php echo wp_kses_post($attributes['eyebrow']); ?></p><?php endif; ?>
			<h2 class="acm__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?> <span><?php echo wp_kses_post($attributes['titleAccent'] ?? ''); ?></span></h2>
			<?php if (!empty($attributes['text'])) : ?><p class="acm__text"><?php echo wp_kses_post($attributes['text']); ?></p><?php endif; ?>
			<?php if (!empty($attributes['btnText'])) : ?>
				<a class="acm__button" href="<?php echo esc_url($btn_url); ?>"<?php echo $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo wp_kses_post($attributes['btnText']); ?> <span aria-hidden="true">↗︎</span></a>
			<?php endif; ?>
		</div>
	</div>
</section>
