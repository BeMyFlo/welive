<?php
defined('ABSPATH') || exit;

if (!function_exists('az_home_img')) :
	// Prefer the Media Library id (survives a domain change), fall back to the stored URL.
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

$images  = $attributes['images'] ?? [];
$metrics = $attributes['metrics'] ?? [];
$cta_url = az_home_link($attributes['ctaUrl'] ?? '');
$wrapper = get_block_wrapper_attributes(['class' => 'hab', 'id' => 'about-welive']);
?>
<section <?php echo $wrapper; ?>>
	<div class="hab__wrap">
		<div class="hab__intro">
			<a class="hab__collage" href="<?php echo esc_url($cta_url); ?>">
				<?php foreach (array_slice($images, 0, 3) as $i => $img) :
					$src = az_home_img($img, 'large');
					if (!$src) {
						continue;
					} ?>
					<img<?php echo $i === 0 ? ' class="hab__collage-main"' : ''; ?> src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($img['alt'] ?? ''); ?>" loading="lazy" />
				<?php endforeach; ?>
			</a>
			<div class="hab__copy">
				<h2 class="hab__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?> <span><?php echo wp_kses_post($attributes['titleAccent'] ?? ''); ?></span></h2>
				<?php if (!empty($attributes['lead'])) : ?><p class="hab__lead"><?php echo wp_kses_post($attributes['lead']); ?></p><?php endif; ?>
				<?php if (!empty($attributes['text'])) : ?><p class="hab__text"><?php echo wp_kses_post($attributes['text']); ?></p><?php endif; ?>
				<?php if (!empty($attributes['ctaText'])) : ?>
					<a class="hab__pill" href="<?php echo esc_url($cta_url); ?>"><?php echo wp_kses_post($attributes['ctaText']); ?> <span aria-hidden="true">↗︎</span></a>
				<?php endif; ?>
			</div>
		</div>
		<?php if ($metrics) : ?>
			<div class="hab__metrics">
				<?php foreach ($metrics as $m) : ?>
					<div><strong><?php echo wp_kses_post($m['value'] ?? ''); ?></strong><p><?php echo wp_kses_post($m['label'] ?? ''); ?></p></div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
