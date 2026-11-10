<?php
defined('ABSPATH') || exit;

$anchor      = $attributes['anchor'] ?? '';
$text        = $attributes['text'] ?? '';
$phone_title = $attributes['phoneTitle'] ?? '';
$phone       = trim((string) ($attributes['phone'] ?? ''));
$placeholder = $attributes['phonePlaceholder'] ?? '';
$note        = $attributes['phoneNote'] ?? '';
$dial        = preg_replace('/[\s().-]/', '', $phone);
$is_phone    = (bool) preg_match('/^(0|\+84)\d{9,10}$/', (string) $dial);

$wrapper = get_block_wrapper_attributes(['class' => 'cin'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="cin__wrap">
		<div class="cin__text">
			<p><?php echo wp_kses_post($text); ?></p>
		</div>
		<aside class="cin__phone">
			<h2><?php echo wp_kses_post($phone_title); ?></h2>
			<?php if ($is_phone) : ?>
				<a class="cin__value" href="tel:<?php echo esc_attr($dial); ?>"><?php echo esc_html($phone); ?></a>
			<?php else : ?>
				<p class="cin__value"><?php echo esc_html($phone !== '' ? $phone : $placeholder); ?></p>
			<?php endif; ?>
			<?php if ($note !== '') : ?>
				<p class="cin__note"><?php echo wp_kses_post($note); ?></p>
			<?php endif; ?>
		</aside>
	</div>
</section>
