<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$subheading = $attributes['subheading'] ?? 'Talent spotlight';
$title      = $attributes['title'] ?? 'OUR ARTISTS';
$artists    = $attributes['artists'] ?? [];
$layout     = $attributes['layout'] ?? 'grid';
$is_wall    = $layout === 'wall';

if ($is_wall) {
	$frame_url = get_stylesheet_directory_uri() . '/assets/images/creator-frame-';
	$tier_of_i = static fn(int $i): string => [0 => 'top1', 1 => 'top2', 2 => 'top3'][$i] ?? 'rest';
	$initials  = static function (string $name): string {
		$words = preg_split('/\s+/u', trim(wp_strip_all_tags($name))) ?: [];
		$out   = '';
		foreach (array_slice($words, 0, 2) as $w) {
			$out .= mb_strtoupper(mb_substr($w, 0, 1));
		}
		return $out;
	};
	$pending_text = $attributes['pendingText'] ?? '';
	$link_labels  = ['tiktok' => 'TikTok', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'threads' => 'Threads'];

	$card = static function (array $artist, int $index) use ($frame_url, $tier_of_i, $initials, $pending_text, $link_labels): string {
		$tier  = $tier_of_i($index);
		$name  = wp_strip_all_tags($artist['name'] ?? '');
		$img   = $artist['imageUrl'] ?? '';
		$pos   = $artist['position'] ?? '50% 42%';
		$links = [];
		foreach ($link_labels as $key => $label) {
			if (!empty($artist['socials'][$key])) {
				$links[] = ['label' => $label, 'url' => $artist['socials'][$key]];
			}
		}
		$class = $index < 3 ? 'aw__card aw__podium-' . ($index + 1) : 'aw__card aw__standard';
		ob_start(); ?>
		<li class="<?php echo esc_attr($class); ?>">
			<button type="button" class="aw__trigger" data-name="<?php echo esc_attr($name); ?>" data-image="<?php echo esc_url($img); ?>" data-position="<?php echo esc_attr($pos); ?>" data-initials="<?php echo esc_attr($initials($name)); ?>" data-bio="<?php echo esc_attr(wp_strip_all_tags($artist['bio'] ?? '')); ?>" data-links="<?php echo esc_attr(wp_json_encode($links)); ?>" aria-label="<?php echo esc_attr($name); ?>">
				<span class="aw__frame">
					<span class="aw__window">
						<?php if ($img) : ?>
							<img class="aw__portrait" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>" style="object-position:<?php echo esc_attr($pos); ?>" loading="lazy" />
						<?php else : ?>
							<span class="aw__pending"><span class="aw__initials" aria-hidden="true"><?php echo esc_html($initials($name)); ?></span><small><?php echo esc_html($pending_text); ?></small></span>
						<?php endif; ?>
					</span>
					<img class="aw__ornament" src="<?php echo esc_url($frame_url . $tier . '.png'); ?>" alt="" aria-hidden="true" />
				</span>
				<span class="aw__caption"><span class="aw__name"><?php echo esc_html($name); ?></span></span>
			</button>
		</li>
		<?php
		return (string) ob_get_clean();
	};

	$podium_order = [1, 0, 2]; // silver | gold (centre) | copper
	$wrapper = get_block_wrapper_attributes(['class' => 'aw', 'id' => 'danh-vong']);
	?>
	<section <?php echo $wrapper; ?>>
		<div class="aw__wrap">
			<div class="aw__heading">
				<h1 class="aw__title"><?php echo wp_kses_post($title); ?><?php echo !empty($attributes['titleAccent']) ? '<br><span>' . wp_kses_post($attributes['titleAccent']) . '</span>' : ''; ?></h1>
				<?php if (!empty($attributes['description'])) : ?><p class="aw__desc"><?php echo wp_kses_post($attributes['description']); ?></p><?php endif; ?>
			</div>

			<ol class="aw__podium">
				<?php foreach ($podium_order as $i) {
					if (isset($artists[$i])) {
						echo $card($artists[$i], $i);
					}
				} ?>
			</ol>

			<?php if (count($artists) > 3) : ?>
				<?php if (!empty($attributes['dividerText'])) : ?><div class="aw__divider"><span><?php echo wp_kses_post($attributes['dividerText']); ?></span></div><?php endif; ?>
				<ol class="aw__grid" start="4">
					<?php foreach (array_slice($artists, 3, null, true) as $i => $artist) {
						echo $card($artist, (int) $i);
					} ?>
				</ol>
			<?php endif; ?>

			<?php if (!empty($attributes['note'])) : ?><p class="aw__note"><?php echo wp_kses_post($attributes['note']); ?></p><?php endif; ?>
		</div>

		<dialog class="aw__dialog" data-fallback="<?php echo esc_attr($attributes['dialogFallback'] ?? ''); ?>" data-eyebrow="<?php echo esc_attr($attributes['dialogEyebrow'] ?? ''); ?>" data-pending="<?php echo esc_attr($pending_text); ?>" aria-label="<?php echo esc_attr__('Giới thiệu', 'ai-zippy-child'); ?>">
			<button type="button" class="aw__close" aria-label="Đóng">×</button>
			<div class="aw__visual"></div>
			<div class="aw__content">
				<p class="aw__eyebrow"></p>
				<h2 class="aw__dname"></h2>
				<p class="aw__bio"></p>
				<div class="aw__links"></div>
			</div>
		</dialog>
	</section>
	<?php
	return;
}

// Wall layout: rows of 3 · 4 · 3 (then 4 per row)
$row_sizes = [3, 4, 3];
$row_start = [];
$row_end   = [];
if ($is_wall) {
	$cursor = 0;
	$total  = count($artists);
	for ($r = 0; $cursor < $total; $r++) {
		$size           = $row_sizes[$r] ?? 4;
		$row_start[$cursor] = $r;
		$row_end[min($cursor + $size, $total) - 1] = $r;
		$cursor        += $size;
	}
	$frame_dir = get_stylesheet_directory_uri() . '/assets/images/creator-frame-';
	$frame_vars = '';
	foreach (['top1', 'top2', 'top3', 'rest'] as $tier) {
		$frame_vars .= '--as-frame-' . $tier . ":url('" . esc_url($frame_dir . $tier . '.png') . "');";
	}
}

// Wall tier: artist 1 = Top 1 (shown centre), 2 = Top 2, 3 = Top 3, the rest share one frame
$tier_of = static fn(int $i): string => [0 => 'top1', 1 => 'top2', 2 => 'top3'][$i] ?? 'rest';

$wrapper = get_block_wrapper_attributes(['class' => 'as has-text-align-' . $text_align . ($is_wall ? ' as--wall' : '')]);
?>
<div <?php echo $wrapper; ?>>
	<div class="as__container">
		<div class="as__header-wrap reveal-on-scroll reveal-fade-up">
			<div class="as__intro">
				<div class="as__subheading-wrap">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="as__subheading-icon">
						<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#EC4600" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<h6 class="as__subheading"><?php echo esc_html($subheading); ?></h6>
				</div>
				<h2 class="as__heading"><?php echo esc_html($title); ?></h2>
			</div>
		</div>

		<div class="as__grid<?php echo $is_wall ? ' as__grid--wall' : ''; ?>"<?php echo $is_wall ? ' style="' . esc_attr($frame_vars) . '"' : ''; ?> data-reveal-stagger=".as__card" data-reveal-delay-base="100">
			<?php foreach ($artists as $index => $artist) :
				$name      = $artist['name'] ?? '';
				$number    = $artist['number'] ?? '01';
				$image_url = $artist['imageUrl'] ?? '';
				$socials   = $artist['socials'] ?? [];
			?>
				<?php if ($is_wall && isset($row_start[$index])) : ?>
					<div class="as__row as__row--<?php echo (int) $row_start[$index]; ?>">
				<?php endif; ?>
				<div class="as__card reveal-item reveal-fade-up<?php echo $is_wall ? ' as__card--framed as__card--' . $tier_of((int) $index) : ''; ?>">
					<div class="as__card-img-wrap">
						<?php if ($image_url) : ?>
							<img class="as__card-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" />
						<?php else : ?>
							<div class="as__card-img-placeholder"></div>
						<?php endif; ?>

						<!-- Hover social icons overlay -->
						<div class="as__card-social-overlay">
							<div class="as__social-icons">
								<?php if (!empty($socials['facebook'])) : ?>
									<a href="<?php echo esc_url($socials['facebook']); ?>" target="_blank" class="as__social-btn">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
											<path d="M9.101 23.691v-10.28H5.617V9.414h3.484V6.486c0-3.456 2.053-5.32 5.093-5.32 1.458 0 2.981.261 2.981.261v3.277h-1.68c-1.71 0-2.244 1.062-2.244 2.15v2.585h3.69l-.59 3.997h-3.1v10.28h-3.69z"/>
										</svg>
									</a>
								<?php endif; ?>

								<?php if (!empty($socials['instagram'])) : ?>
									<a href="<?php echo esc_url($socials['instagram']); ?>" target="_blank" class="as__social-btn">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
											<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
											<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
											<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
										</svg>
									</a>
								<?php endif; ?>

								<?php if (!empty($socials['tiktok'])) : ?>
									<a href="<?php echo esc_url($socials['tiktok']); ?>" target="_blank" class="as__social-btn">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
											<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.74 4.07a6.255 6.255 0 0 0 4.19 1.64v3.91a9.928 9.928 0 0 1-5.18-1.57v7.6c.06 4.31-3.1 8.23-7.4 8.78-4.49.62-8.89-2.31-9.7-6.73-.85-4.41 1.77-9.01 6.07-10.27.18-.05.37-.1.56-.14v3.98a5.955 5.955 0 0 0-2.82 2.61c-1.39 2.51-.55 5.79 1.94 7.23a6.012 6.012 0 0 0 8.08-2.21c.54-.93.8-1.99.78-3.06V0h-.48z"/>
										</svg>
									</a>
								<?php endif; ?>

								<?php if (!empty($socials['threads'])) : ?>
									<a href="<?php echo esc_url($socials['threads']); ?>" target="_blank" class="as__social-btn">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
											<path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 14.5c-1.5 0-3-1-3-2.5s1.5-2.5 3-2.5 3 1 3 2.5-1.5 2.5-3 2.5zm0-8c-2.5 0-5 2-5 5.5s2.5 5.5 5 5.5h2v-1.5h-2c-1.5 0-3.5-1-3.5-4s2-4 3.5-4 3.5 1 3.5 4v1.5a1.5 1.5 0 0 1-3 0V11a2.5 2.5 0 0 0-5 0v3a5.5 5.5 0 0 0 10 0v-3c0-3.5-2.5-5.5-5-5.5z"/>
										</svg>
									</a>
								<?php endif; ?>

								<?php if (!empty($socials['youtube'])) : ?>
									<a href="<?php echo esc_url($socials['youtube']); ?>" target="_blank" class="as__social-btn">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
											<path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.108C19.53 3.5 12 3.5 12 3.5s-7.53 0-9.388.555A3.003 3.003 0 0 0 .502 6.163C0 8.07 0 12 0 12s0 3.93.502 5.837a3.003 3.003 0 0 0 2.11 2.108C4.47 20.5 12 20.5 12 20.5s7.53 0 9.388-.555a3.003 3.003 0 0 0 2.11-2.108C24 15.93 24 12 24 12s0-3.93-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
										</svg>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="as__card-info">
						<div class="as__card-meta">
							<span class="as__card-number"><?php echo esc_html($number); ?></span>
						</div>
						<h3 class="as__card-name"><?php echo esc_html($name); ?></h3>
					</div>
				</div>

				<!-- Popup Modal -->
				<div class="as__modal" id="as-modal-<?php echo $index; ?>" aria-hidden="true">
					<div class="as__modal-overlay"></div>
					<div class="as__modal-container">
						<div class="as__modal-content">
							<div class="as__modal-left">
								<?php if ($image_url) : ?>
									<img class="as__modal-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" />
								<?php else : ?>
									<div class="as__modal-img-placeholder"></div>
								<?php endif; ?>
							</div>
							<div class="as__modal-right">
								<button class="as__modal-close" aria-label="Close modal">&times;</button>
								<span class="as__modal-number"><?php echo esc_html($number); ?></span>
								<h3 class="as__modal-name"><?php echo esc_html($name); ?></h3>
								<div class="as__modal-bio">
									<?php echo wp_kses_post(nl2br($artist['bio'] ?? '')); ?>
								</div>
								
								<!-- Social icons -->
								<div class="as__modal-socials">
									<?php if (!empty($socials['facebook'])) : ?>
										<a href="<?php echo esc_url($socials['facebook']); ?>" target="_blank" class="as__social-btn">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path d="M9.101 23.691v-10.28H5.617V9.414h3.484V6.486c0-3.456 2.053-5.32 5.093-5.32 1.458 0 2.981.261 2.981.261v3.277h-1.68c-1.71 0-2.244 1.062-2.244 2.15v2.585h3.69l-.59 3.997h-3.1v10.28h-3.69z"/>
											</svg>
										</a>
									<?php endif; ?>

									<?php if (!empty($socials['instagram'])) : ?>
										<a href="<?php echo esc_url($socials['instagram']); ?>" target="_blank" class="as__social-btn">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
												<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
												<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
												<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
											</svg>
										</a>
									<?php endif; ?>

									<?php if (!empty($socials['tiktok'])) : ?>
										<a href="<?php echo esc_url($socials['tiktok']); ?>" target="_blank" class="as__social-btn">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.74 4.07a6.255 6.255 0 0 0 4.19 1.64v3.91a9.928 9.928 0 0 1-5.18-1.57v7.6c.06 4.31-3.1 8.23-7.4 8.78-4.49.62-8.89-2.31-9.7-6.73-.85-4.41 1.77-9.01 6.07-10.27.18-.05.37-.1.56-.14v3.98a5.955 5.955 0 0 0-2.82 2.61c-1.39 2.51-.55 5.79 1.94 7.23a6.012 6.012 0 0 0 8.08-2.21c.54-.93.8-1.99.78-3.06V0h-.48z"/>
											</svg>
										</a>
									<?php endif; ?>

									<?php if (!empty($socials['threads'])) : ?>
										<a href="<?php echo esc_url($socials['threads']); ?>" target="_blank" class="as__social-btn">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
												<path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 14.5c-1.5 0-3-1-3-2.5s1.5-2.5 3-2.5 3 1 3 2.5-1.5 2.5-3 2.5zm0-8c-2.5 0-5 2-5 5.5s2.5 5.5 5 5.5h2v-1.5h-2c-1.5 0-3.5-1-3.5-4s2-4 3.5-4 3.5 1 3.5 4v1.5a1.5 1.5 0 0 1-3 0V11a2.5 2.5 0 0 0-5 0v3a5.5 5.5 0 0 0 10 0v-3c0-3.5-2.5-5.5-5-5.5z"/>
											</svg>
										</a>
									<?php endif; ?>

									<?php if (!empty($socials['youtube'])) : ?>
										<a href="<?php echo esc_url($socials['youtube']); ?>" target="_blank" class="as__social-btn">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.108C19.53 3.5 12 3.5 12 3.5s-7.53 0-9.388.555A3.003 3.003 0 0 0 .502 6.163C0 8.07 0 12 0 12s0 3.93.502 5.837a3.003 3.003 0 0 0 2.11 2.108C4.47 20.5 12 20.5 12 20.5s7.53 0 9.388-.555a3.003 3.003 0 0 0 2.11-2.108C24 15.93 24 12 24 12s0-3.93-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
											</svg>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ($is_wall && isset($row_end[$index])) : ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
