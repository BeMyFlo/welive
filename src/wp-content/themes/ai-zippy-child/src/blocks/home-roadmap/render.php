<?php
defined('ABSPATH') || exit;

if (!function_exists('az_home_link')) :
	function az_home_link(string $url): string
	{
		return $url === '' ? '#' : (str_starts_with($url, '/') ? home_url($url) : $url);
	}
endif;

$cards    = $attributes['cards'] ?? [];
$cta_url  = az_home_link($attributes['ctaUrl'] ?? '');
$card_cta = $attributes['cardCta'] ?? '';
$anchor   = $attributes['anchor'] ?? '';
$is_tabs  = ($attributes['layout'] ?? 'cards') === 'tabs';
$wrapper  = get_block_wrapper_attributes(['class' => 'hrm' . ($is_tabs ? ' hrm--tabs' : '')] + ($anchor ? ['id' => $anchor] : []));
$tabs     = $attributes['tabs'] ?? [];
$photo    = is_array($attributes['image'] ?? null) ? $attributes['image'] : [];
$photo_id = (int) ($photo['imageId'] ?? 0);
$photo_url = $photo_id ? wp_get_attachment_image_url($photo_id, 'large') : '';
$photo_url = $photo_url ?: ($photo['imageUrl'] ?? '');
$uid      = 'hrm-' . wp_unique_id();
?>
<section <?php echo $wrapper; ?>>
	<div class="hrm__wrap">
		<div class="hrm__top">
			<div>
				<?php if (!empty($attributes['kicker'])) : ?><p class="hrm__kicker"><?php echo wp_kses_post($attributes['kicker']); ?></p><?php endif; ?>
				<h2 class="hrm__title"><?php echo wp_kses_post($attributes['title'] ?? ''); ?></h2>
			</div>
			<?php if (!empty($attributes['ctaText'])) : ?>
				<a class="hrm__pill" href="<?php echo esc_url($cta_url); ?>"><?php echo wp_kses_post($attributes['ctaText']); ?> <span aria-hidden="true">↗︎</span></a>
			<?php endif; ?>
		</div>
		<?php if (!empty($attributes['intro'])) : ?><p class="hrm__intro"><?php echo wp_kses_post($attributes['intro']); ?></p><?php endif; ?>
		<?php if ($is_tabs) : ?>
			<div class="hrm__tabs" role="tablist" aria-label="<?php echo esc_attr(wp_strip_all_tags($attributes['title'] ?? '')); ?>">
				<?php foreach ($tabs as $i => $tab) : ?>
					<button class="hrm__tab" type="button" role="tab" id="<?php echo esc_attr($uid . '-tab-' . $i); ?>" aria-controls="<?php echo esc_attr($uid . '-panel-' . $i); ?>" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"<?php echo $i === 0 ? '' : ' tabindex="-1"'; ?>>
						<span class="hrm__days"><?php echo wp_kses_post($tab['days'] ?? ''); ?></span>
						<strong><?php echo wp_kses_post($tab['label'] ?? ''); ?></strong>
					</button>
				<?php endforeach; ?>
			</div>
			<div class="hrm__detail-box">
				<div class="hrm__panels">
					<?php foreach ($tabs as $i => $tab) : ?>
						<div class="hrm__panel" id="<?php echo esc_attr($uid . '-panel-' . $i); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($uid . '-tab-' . $i); ?>" tabindex="0"<?php echo $i === 0 ? '' : ' hidden'; ?>>
							<p class="hrm__days"><?php echo wp_kses_post($tab['days'] ?? ''); ?></p>
							<h3><?php echo wp_kses_post($tab['panelTitle'] ?? ''); ?></h3>
							<ul>
								<?php foreach (($tab['bullets'] ?? []) as $li) : ?>
									<li><?php echo wp_kses_post($li); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ($photo_url) : ?>
					<img class="hrm__photo" src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($photo['alt'] ?? ''); ?>" loading="lazy" />
				<?php endif; ?>
			</div>
			<?php if (!empty($attributes['note'])) : ?><p class="hrm__note"><?php echo wp_kses_post($attributes['note']); ?></p><?php endif; ?>
		<?php else : ?>
		<div class="hrm__cards">
			<?php foreach ($cards as $card) : ?>
				<a class="hrm__card" href="<?php echo esc_url(az_home_link($card['url'] ?: ($attributes['ctaUrl'] ?? ''))); ?>">
					<span class="hrm__index"><?php echo wp_kses_post($card['index'] ?? ''); ?></span>
					<div>
						<p class="hrm__days"><?php echo wp_kses_post($card['days'] ?? ''); ?></p>
						<h3 class="hrm__card-title"><?php echo wp_kses_post($card['title'] ?? ''); ?></h3>
						<span class="hrm__detail"><?php echo wp_kses_post($card['detail'] ?? ''); ?></span>
					</div>
					<?php if ($card_cta !== '') : ?><span class="hrm__card-cta"><?php echo wp_kses_post($card_cta); ?> <b aria-hidden="true">↗︎</b></span><?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
