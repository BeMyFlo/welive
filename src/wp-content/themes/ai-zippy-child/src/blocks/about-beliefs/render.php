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

$items   = $attributes['items'] ?? [];
$src     = az_home_img($attributes, 'large');
$wrapper = get_block_wrapper_attributes(['class' => 'abf', 'id' => 'gia-tri']);
?>
<section <?php echo $wrapper; ?>>
	<div class="abf__wrap">
		<figure class="abf__photo">
			<?php if ($src) : ?><img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($attributes['alt'] ?? ''); ?>" loading="lazy" /><?php endif; ?>
			<?php if (!empty($attributes['caption'])) : ?><figcaption><?php echo wp_kses_post($attributes['caption']); ?></figcaption><?php endif; ?>
		</figure>
		<div class="abf__copy">
			<?php if (!empty($attributes['eyebrow'])) : ?><p class="abf__eyebrow"><?php echo wp_kses_post($attributes['eyebrow']); ?></p><?php endif; ?>
			<h2 class="abf__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?> <span><?php echo wp_kses_post($attributes['titleAccent'] ?? ''); ?></span></h2>
			<ol class="abf__list">
				<?php foreach ($items as $i => $it) : ?>
					<li>
						<span><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
						<div><h3><?php echo wp_kses_post($it['title'] ?? ''); ?></h3><p><?php echo wp_kses_post($it['text'] ?? ''); ?></p></div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
