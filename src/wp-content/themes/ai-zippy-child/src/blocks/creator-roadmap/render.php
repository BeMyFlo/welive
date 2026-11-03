<?php
defined('ABSPATH') || exit;

$anchor      = $attributes['anchor'] ?? '';
$badge       = $attributes['badge'] ?? '';
$title       = $attributes['title'] ?? '';
$title_accent = $attributes['titleAccent'] ?? '';
$description = $attributes['description'] ?? '';
$tabs        = $attributes['tabs'] ?? [];
$footnote    = $attributes['footnote'] ?? '';
$link_text   = $attributes['linkText'] ?? '';
$link_url    = $attributes['linkUrl'] ?? '';

$resolve = static fn(string $url): string => $url === '' ? '#' : (str_starts_with($url, '/') ? home_url($url) : $url);

$wrapper = get_block_wrapper_attributes(['class' => 'crm'] + ($anchor ? ['id' => $anchor] : []));
?>
<div <?php echo $wrapper; ?>>
	<div class="crm__container">
		<div class="crm__header reveal-on-scroll reveal-fade-up">
			<div class="crm__intro">
				<?php if ($badge !== '') : ?>
					<span class="crm__badge"><svg class="crm__badge-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="19" r="2.5"/><circle cx="18" cy="5" r="2.5"/><path d="M8.5 19H15a3.5 3.5 0 0 0 0-7H9a3.5 3.5 0 0 1 0-7h6.5"/></svg><span><?php echo wp_kses_post($badge); ?></span></span>
				<?php endif; ?>
				<h2 class="crm__title"><?php echo wp_kses_post($title); ?><span class="crm__title-accent"><?php echo wp_kses_post($title_accent); ?></span></h2>
			</div>
			<?php if ($description !== '') : ?>
				<p class="crm__desc"><?php echo wp_kses_post($description); ?></p>
			<?php endif; ?>
		</div>

		<div class="crm__tabs reveal-on-scroll reveal-fade-up" role="tablist" data-reveal-delay="100">
			<?php foreach ($tabs as $i => $tab) : ?>
				<button type="button" role="tab" class="crm__tab<?php echo $i === 0 ? ' is-active' : ''; ?>" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>">
					<span class="crm__tab-label"><?php echo wp_kses_post($tab['label'] ?? ''); ?></span>
					<span class="crm__tab-name"><?php echo wp_kses_post($tab['name'] ?? ''); ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="crm__panels reveal-on-scroll reveal-fade-up" data-reveal-delay="150">
			<?php foreach ($tabs as $i => $tab) :
				$image_url = $tab['imageUrl'] ?? '';
				$bullets   = array_filter((array) ($tab['bullets'] ?? []), 'strlen');
			?>
				<div class="crm__panel<?php echo $i === 0 ? ' is-active' : ''; ?>" role="tabpanel">
					<div class="crm__panel-text">
						<span class="crm__panel-label"><?php echo wp_kses_post($tab['label'] ?? ''); ?></span>
						<h3 class="crm__panel-heading"><?php echo wp_kses_post($tab['heading'] ?? ''); ?></h3>
						<?php if ($bullets) : ?>
							<ul class="crm__list">
								<?php foreach ($bullets as $bullet) : ?>
									<li><?php echo wp_kses_post($bullet); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
					<div class="crm__panel-media">
						<?php if ($image_url) : ?>
							<img src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy" />
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ($footnote !== '' || $link_text !== '') : ?>
			<div class="crm__footer">
				<p class="crm__footnote"><?php echo wp_kses_post($footnote); ?></p>
				<?php if ($link_text !== '') : ?>
					<a class="crm__link" href="<?php echo esc_url($resolve($link_url)); ?>">
						<span><?php echo wp_kses_post($link_text); ?></span>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17L17 7M17 7H8M17 7V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
