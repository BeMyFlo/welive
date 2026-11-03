<?php
defined('ABSPATH') || exit;

$eyebrow      = $attributes['eyebrow'] ?? '';
$title        = $attributes['title'] ?? '';
$title_accent = $attributes['titleAccent'] ?? '';
$bullets      = array_filter((array) ($attributes['bullets'] ?? []), 'strlen');
$card_label   = $attributes['cardLabel'] ?? '';
$card_kicker  = $attributes['cardKicker'] ?? '';
$card_title   = $attributes['cardTitle'] ?? '';
$card_title2  = $attributes['cardTitle2'] ?? '';
$photos       = $attributes['photos'] ?? [];
$avatars      = $attributes['avatars'] ?? [];
$avatar_text  = $attributes['avatarText'] ?? '';
$btn_text     = $attributes['btnText'] ?? '';
$btn_url      = $attributes['btnUrl'] ?? '';

// Logo: the block's own image, else the site logo.
$logo_url = $attributes['logoUrl'] ?? '';
if ($logo_url === '' && get_theme_mod('custom_logo')) {
	$logo_url = (string) wp_get_attachment_image_url((int) get_theme_mod('custom_logo'), 'full');
}

$resolve = static fn(string $url): string => $url === '' ? '#' : (str_starts_with($url, '/') ? home_url($url) : $url);

$wrapper = get_block_wrapper_attributes(['class' => 'ccm']);
?>
<div <?php echo $wrapper; ?>>
	<div class="ccm__container">
		<div class="ccm__card reveal-on-scroll reveal-fade-up">
			<div class="ccm__card-top">
				<?php if ($logo_url) : ?>
					<img class="ccm__logo" src="<?php echo esc_url($logo_url); ?>" alt="" />
				<?php endif; ?>
				<span class="ccm__card-label"><?php echo wp_kses_post($card_label); ?></span>
			</div>
			<div class="ccm__photos">
				<?php foreach (array_slice($photos, 0, 3) as $i => $photo) : ?>
					<div class="ccm__photo ccm__photo--<?php echo (int) $i + 1; ?>">
						<?php if (!empty($photo['imageUrl'])) : ?>
							<img src="<?php echo esc_url($photo['imageUrl']); ?>" alt="" loading="lazy" />
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="ccm__card-text">
				<span class="ccm__kicker"><?php echo wp_kses_post($card_kicker); ?></span>
				<strong class="ccm__card-title"><?php echo wp_kses_post($card_title); ?><br><?php echo wp_kses_post($card_title2); ?></strong>
			</div>
		</div>

		<div class="ccm__content reveal-on-scroll reveal-fade-up" data-reveal-delay="150">
			<span class="ccm__eyebrow"><?php echo wp_kses_post($eyebrow); ?></span>
			<h2 class="ccm__title"><?php echo wp_kses_post($title); ?> <span class="ccm__title-accent"><?php echo wp_kses_post($title_accent); ?></span></h2>
			<?php if ($bullets) : ?>
				<ul class="ccm__list">
					<?php foreach ($bullets as $bullet) : ?>
						<li><?php echo wp_kses_post($bullet); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<div class="ccm__actions">
				<div class="ccm__people">
					<span class="ccm__avatars">
						<?php foreach ($avatars as $avatar) : ?>
							<span class="ccm__avatar"><?php if (!empty($avatar['imageUrl'])) : ?><img src="<?php echo esc_url($avatar['imageUrl']); ?>" alt="" /><?php endif; ?></span>
						<?php endforeach; ?>
					</span>
					<span class="ccm__people-text"><?php echo wp_kses_post($avatar_text); ?></span>
				</div>
				<?php if ($btn_text !== '') : ?>
					<a class="ccm__btn" href="<?php echo esc_url($resolve($btn_url)); ?>">
						<span><?php echo wp_kses_post($btn_text); ?></span>
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17L17 7M17 7H8M17 7V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
