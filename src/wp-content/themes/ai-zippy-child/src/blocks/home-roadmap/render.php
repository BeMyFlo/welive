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
$wrapper  = get_block_wrapper_attributes(['class' => 'hrm'] + ($anchor ? ['id' => $anchor] : []));
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
	</div>
</section>
