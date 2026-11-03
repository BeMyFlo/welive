<?php
defined('ABSPATH') || exit;

$badge        = $attributes['badge'] ?? '';
$title        = $attributes['title'] ?? '';
$title_accent = $attributes['titleAccent'] ?? '';
$items        = $attributes['items'] ?? [];
$ph_count     = max(0, (int) ($attributes['placeholderCount'] ?? 0));
$ph_title     = $attributes['placeholderTitle'] ?? '';
$ph_text      = $attributes['placeholderText'] ?? '';
$footnote     = $attributes['footnote'] ?? '';

$resolve = static fn(string $url): string => $url === '' ? '' : (str_starts_with($url, '/') ? home_url($url) : $url);

$wrapper = get_block_wrapper_attributes(['class' => 'cwl']);
?>
<div <?php echo $wrapper; ?>>
	<div class="cwl__container">
		<div class="cwl__header reveal-on-scroll reveal-fade-up">
			<div class="cwl__intro">
				<?php if ($badge !== '') : ?>
					<span class="cwl__badge"><svg class="cwl__badge-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0z"/><path d="M17 5h3v2a3 3 0 0 1-3 3M7 5H4v2a3 3 0 0 0 3 3"/></svg><span><?php echo wp_kses_post($badge); ?></span></span>
				<?php endif; ?>
				<h2 class="cwl__title"><?php echo wp_kses_post($title); ?><span class="cwl__title-accent"><?php echo wp_kses_post($title_accent); ?></span></h2>
			</div>
			<div class="cwl__arrows">
				<button type="button" class="cwl__arrow cwl__arrow--prev" aria-label="Previous">←</button>
				<button type="button" class="cwl__arrow cwl__arrow--next" aria-label="Next">→</button>
			</div>
		</div>

		<div class="cwl__track reveal-on-scroll reveal-fade-up" data-reveal-delay="100">
			<?php foreach ($items as $item) :
				$image_url = $item['imageUrl'] ?? '';
				$url       = $resolve($item['linkUrl'] ?? '');
				$tag       = $url !== '' ? 'a' : 'div';
			?>
				<<?php echo $tag; ?> class="cwl__card"<?php echo $url !== '' ? ' href="' . esc_url($url) . '"' : ''; ?>>
					<?php if ($image_url) : ?>
						<img class="cwl__card-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(wp_strip_all_tags($item['name'] ?? '')); ?>" loading="lazy" />
					<?php endif; ?>
					<div class="cwl__card-info">
						<strong class="cwl__card-name"><?php echo wp_kses_post($item['name'] ?? ''); ?></strong>
						<span class="cwl__card-link"><?php echo wp_kses_post($item['linkText'] ?? ''); ?><?php echo $url !== '' ? ' ↗' : ''; ?></span>
					</div>
				</<?php echo $tag; ?>>
			<?php endforeach; ?>
			<?php for ($i = 0; $i < $ph_count; $i++) : ?>
				<div class="cwl__card cwl__card--placeholder">
					<span class="cwl__mark">W.</span>
					<div class="cwl__card-info">
						<strong class="cwl__card-name"><?php echo wp_kses_post($ph_title); ?></strong>
						<span class="cwl__card-link"><?php echo wp_kses_post($ph_text); ?></span>
					</div>
				</div>
			<?php endfor; ?>
		</div>

		<?php if ($footnote !== '') : ?>
			<p class="cwl__footnote"><?php echo wp_kses_post($footnote); ?></p>
		<?php endif; ?>
	</div>
</div>
