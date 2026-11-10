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
				<?php $tag = ($attributes['linkStyle'] ?? 'link') === 'tag'; ?>
				<a class="hag__link<?php echo $tag ? ' hag__link--tag' : ''; ?>" href="<?php echo esc_url($default); ?>"><?php if ($tag) { echo az_home_tag_icon((string) ($attributes['linkIcon'] ?? 'none')); // phpcs:ignore WordPress.Security.EscapeOutput
				} ?><?php echo wp_kses_post($attributes['linkText']); ?><?php if (!$tag) : ?> <span aria-hidden="true">↗︎</span><?php endif; ?></a>
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
						<span><?php echo wp_kses_post($card['text'] ?? ''); ?> <b aria-hidden="true">↗︎</b></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
