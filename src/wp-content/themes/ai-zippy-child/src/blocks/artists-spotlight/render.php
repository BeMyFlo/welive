<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$subheading = $attributes['subheading'] ?? 'Talent spotlight';
$title      = $attributes['title'] ?? 'OUR ARTISTS';
$artists    = $attributes['artists'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'as has-text-align-' . $text_align]);
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

		<div class="as__grid" data-reveal-stagger=".as__card" data-reveal-delay-base="100">
			<?php foreach ($artists as $index => $artist) :
				$name      = $artist['name'] ?? '';
				$number    = $artist['number'] ?? '01';
				$image_url = $artist['imageUrl'] ?? '';
				$socials   = $artist['socials'] ?? [];
			?>
				<div class="as__card reveal-item reveal-fade-up">
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
			<?php endforeach; ?>
		</div>
	</div>
</div>
