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

$cards   = $attributes['cards'] ?? [];
$default = az_home_link($attributes['linkUrl'] ?? '');
$anchor  = $attributes['anchor'] ?? '';
$wrapper = get_block_wrapper_attributes(['class' => 'hag'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="hag__wrap">
		<div class="hag__top">
			<div>
				<?php if (!empty($attributes['kicker'])) : ?><p class="hag__kicker"><?php echo wp_kses_post($attributes['kicker']); ?></p><?php endif; ?>
				<h2 class="hag__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?> <span><?php echo wp_kses_post($attributes['titleAccent'] ?? ''); ?></span></h2>
			</div>
			<?php if (!empty($attributes['linkText'])) : ?>
				<a class="hag__link" href="<?php echo esc_url($default); ?>"><?php echo wp_kses_post($attributes['linkText']); ?> <span aria-hidden="true">↗</span></a>
			<?php endif; ?>
		</div>
		<div class="hag__grid">
			<?php foreach ($cards as $card) :
				$src  = az_home_img($card, 'large');
				$href = !empty($card['url']) ? az_home_link($card['url']) : $default; ?>
				<a class="hag__card" href="<?php echo esc_url($href); ?>">
					<?php if ($src) : ?><img src="<?php echo esc_url($src); ?>" alt="" loading="lazy" /><?php endif; ?>
					<div>
						<p><?php echo wp_kses_post($card['label'] ?? ''); ?></p>
						<h3><?php echo wp_kses_post($card['title'] ?? ''); ?></h3>
						<span><?php echo wp_kses_post($card['text'] ?? ''); ?> <b aria-hidden="true">↗</b></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
