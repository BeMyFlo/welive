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

$services = $attributes['services'] ?? [];
$stats    = $attributes['stats'] ?? [];
$btn_url  = az_home_link($attributes['btnUrl'] ?? '');
$external = !str_starts_with($btn_url, home_url());
$compact  = ($attributes['variant'] ?? 'classic') === 'compact';
$wrapper  = get_block_wrapper_attributes(['class' => 'ahs' . ($compact ? ' ahs--compact' : '')]);
?>
<section <?php echo $wrapper; ?>>
	<div class="ahs__wrap">
		<div class="ahs__main">
			<div class="ahs__copy">
				<?php if (!empty($attributes['eyebrow'])) : ?><p class="ahs__eyebrow"><span aria-hidden="true">↗︎</span> <?php echo wp_kses_post($attributes['eyebrow']); ?></p><?php endif; ?>
				<h1 class="ahs__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?><?php if (!empty($attributes['titleAccent'])) : ?> <span><?php echo wp_kses_post($attributes['titleAccent']); ?></span><?php endif; ?></h1>
				<?php if (!empty($attributes['description'])) : ?><p class="ahs__desc"><?php echo wp_kses_post($attributes['description']); ?></p><?php endif; ?>
				<?php if (!empty($attributes['btnText'])) : ?>
					<a class="ahs__button" href="<?php echo esc_url($btn_url); ?>"<?php echo $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo wp_kses_post($attributes['btnText']); ?><?php if (!$compact) : ?> <span aria-hidden="true">↗︎</span><?php endif; ?></a>
				<?php endif; ?>
			</div>
			<div class="ahs__services">
				<?php foreach ($services as $s) :
					$src = az_home_img($s, 'large'); ?>
					<article class="ahs__service">
						<div class="ahs__photo">
							<?php if ($src) : ?><img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($s['alt'] ?? ''); ?>" loading="lazy" /><?php endif; ?>
							<?php if (!empty($s['tag'])) : ?><span class="ahs__tag"><?php echo wp_kses_post($s['tag']); ?></span><?php endif; ?>
						</div>
						<div class="ahs__service-copy">
							<h2><?php echo wp_kses_post($s['title'] ?? ''); ?></h2>
							<p><?php echo wp_kses_post($s['text'] ?? ''); ?></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
		<?php if ($stats) : ?>
			<div class="ahs__stats">
				<?php foreach ($stats as $st) : ?>
					<div><strong><?php echo wp_kses_post($st['value'] ?? ''); ?></strong><p><?php echo wp_kses_post($st['label'] ?? ''); ?></p></div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
