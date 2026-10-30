<?php

defined('ABSPATH') || exit;

$artists = $attributes['artists'] ?? [];
$wrapper = get_block_wrapper_attributes(['class' => 'ag']);
?>
<div <?php echo $wrapper; ?>>
	<div class="ag__container">
		<div class="ag__grid" data-reveal-stagger=".ag__card" data-reveal-delay-base="100">
			<?php foreach ($artists as $index => $artist) :
				$name         = $artist['name'] ?? '';
				$number       = $artist['number'] ?? '01';
				$image_url    = $artist['imageUrl'] ?? '';
				$socials      = $artist['socials'] ?? [];
				
				$real_name    = $artist['realName'] ?? '';
				$dob          = $artist['dob'] ?? '';
				$genre        = $artist['genre'] ?? '';
				$overview     = $artist['overview'] ?? '';
				$label_preset = $artist['labelPreset'] ?? 'dao';
				$custom_logo  = $artist['labelLogoUrl'] ?? '';
			?>
				<!-- Artist Card Item -->
				<div class="ag__card reveal-item reveal-fade-up" data-artist-index="<?php echo esc_attr($index); ?>">
					<div class="ag__card-img-wrap">
						<?php if ($image_url) : ?>
							<img class="ag__card-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" />
						<?php else : ?>
							<div class="ag__card-placeholder"></div>
						<?php endif; ?>
						<div class="ag__card-overlay"></div>
						
						<!-- Hover Socials Icon Bar -->
						<div class="ag__card-socials" onclick="event.stopPropagation();">
							<div class="ag__social-list">
								<?php if (!empty($socials['facebook'])) : ?>
									<a href="<?php echo esc_url($socials['facebook']); ?>" target="_blank" class="ag__social-link">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
									</a>
								<?php endif; ?>

								<?php if (!empty($socials['instagram'])) : ?>
									<a href="<?php echo esc_url($socials['instagram']); ?>" target="_blank" class="ag__social-link">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
									</a>
								<?php endif; ?>

								<?php if (!empty($socials['tiktok'])) : ?>
									<a href="<?php echo esc_url($socials['tiktok']); ?>" target="_blank" class="ag__social-link">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.74 4.07a6.255 6.255 0 0 0 4.19 1.64v3.91a9.928 9.928 0 0 1-5.18-1.57v7.6c.06 4.31-3.1 8.23-7.4 8.78-4.49.62-8.89-2.31-9.7-6.73-.85-4.41 1.77-9.01 6.07-10.27.18-.05.37-.1.56-.14v3.98a5.955 5.955 0 0 0-2.82 2.61c-1.39 2.51-.55 5.79 1.94 7.23a6.012 6.012 0 0 0 8.08-2.21c.54-.93.8-1.99.78-3.06V0h-.48z"/></svg>
									</a>
								<?php endif; ?>

								<?php if (!empty($socials['threads'])) : ?>
									<a href="<?php echo esc_url($socials['threads']); ?>" target="_blank" class="ag__social-link">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 14.5c-1.5 0-3-1-3-2.5s1.5-2.5 3-2.5 3 1 3 2.5-1.5 2.5-3 2.5zm0-8c-2.5 0-5 2-5 5.5s2.5 5.5 5 5.5h2v-1.5h-2c-1.5 0-3.5-1-3.5-4s2-4 3.5-4 3.5 1 3.5 4v1.5a1.5 1.5 0 0 1-3 0V11a2.5 2.5 0 0 0-5 0v3a5.5 5.5 0 0 0 10 0v-3c0-3.5-2.5-5.5-5-5.5z"/></svg>
									</a>
								<?php endif; ?>

								<?php if (!empty($socials['youtube'])) : ?>
									<a href="<?php echo esc_url($socials['youtube']); ?>" target="_blank" class="ag__social-link">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.108C19.53 3.5 12 3.5 12 3.5s-7.53 0-9.388.555A3.003 3.003 0 0 0 .502 6.163C0 8.07 0 12 0 12s0 3.93.502 5.837a3.003 3.003 0 0 0 2.11 2.108C4.47 20.5 12 20.5 12 20.5s7.53 0 9.388-.555a3.003 3.003 0 0 0 2.11-2.108C24 15.93 24 12 24 12s0-3.93-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
									</a>
								<?php endif; ?>
							</div>
							
							<div class="ag__info-trigger" data-trigger-index="<?php echo esc_attr($index); ?>">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
							</div>
						</div>
					</div>
					<h4 class="ag__card-name"><?php echo esc_html($name); ?></h4>
				</div>

				<!-- Artist Popup Lightbox Modal -->
				<div class="ag__popup-wrap" id="ag-popup-<?php echo esc_attr($index); ?>">
					<div class="ag__popup-overlay"></div>
					<div class="ag__popup-content">
						<button class="ag__popup-close" aria-label="Close popup">&times;</button>
						
						<div class="ag__popup-body">
							<!-- Left Side: Graphic & Socials -->
							<div class="ag__popup-left">
								<div class="ag__popup-header-mobile">
									<h2 class="ag__popup-name"><?php echo esc_html($name); ?></h2>
									<div class="ag__popup-socials">
										<?php if (!empty($socials['facebook'])) : ?>
											<a href="<?php echo esc_url($socials['facebook']); ?>" target="_blank" class="ag__popup-social-btn">
												<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
											</a>
										<?php endif; ?>
										<?php if (!empty($socials['instagram'])) : ?>
											<a href="<?php echo esc_url($socials['instagram']); ?>" target="_blank" class="ag__popup-social-btn">
												<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
											</a>
										<?php endif; ?>
										<?php if (!empty($socials['tiktok'])) : ?>
											<a href="<?php echo esc_url($socials['tiktok']); ?>" target="_blank" class="ag__popup-social-btn">
												<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.74 4.07a6.255 6.255 0 0 0 4.19 1.64v3.91a9.928 9.928 0 0 1-5.18-1.57v7.6c.06 4.31-3.1 8.23-7.4 8.78-4.49.62-8.89-2.31-9.7-6.73-.85-4.41 1.77-9.01 6.07-10.27.18-.05.37-.1.56-.14v3.98a5.955 5.955 0 0 0-2.82 2.61c-1.39 2.51-.55 5.79 1.94 7.23a6.012 6.012 0 0 0 8.08-2.21c.54-.93.8-1.99.78-3.06V0h-.48z"/></svg>
											</a>
										<?php endif; ?>
										<?php if (!empty($socials['threads'])) : ?>
											<a href="<?php echo esc_url($socials['threads']); ?>" target="_blank" class="ag__popup-social-btn">
												<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 14.5c-1.5 0-3-1-3-2.5s1.5-2.5 3-2.5 3 1 3 2.5-1.5 2.5-3 2.5zm0-8c-2.5 0-5 2-5 5.5s2.5 5.5 5 5.5h2v-1.5h-2c-1.5 0-3.5-1-3.5-4s2-4 3.5-4 3.5 1 3.5 4v1.5a1.5 1.5 0 0 1-3 0V11a2.5 2.5 0 0 0-5 0v3a5.5 5.5 0 0 0 10 0v-3c0-3.5-2.5-5.5-5-5.5z"/></svg>
											</a>
										<?php endif; ?>
										<?php if (!empty($socials['youtube'])) : ?>
											<a href="<?php echo esc_url($socials['youtube']); ?>" target="_blank" class="ag__popup-social-btn">
												<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.108C19.53 3.5 12 3.5 12 3.5s-7.53 0-9.388.555A3.003 3.003 0 0 0 .502 6.163C0 8.07 0 12 0 12s0 3.93.502 5.837a3.003 3.003 0 0 0 2.11 2.108C4.47 20.5 12 20.5 12 20.5s7.53 0 9.388-.555a3.003 3.003 0 0 0 2.11-2.108C24 15.93 24 12 24 12s0-3.93-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
											</a>
										<?php endif; ?>
									</div>
								</div>
								
								<div class="ag__popup-img-wrap">
									<div class="ag__popup-img-overlay"></div>
									<?php if ($image_url) : ?>
										<img class="ag__popup-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" />
									<?php endif; ?>
								</div>
							</div>
							
							<!-- Right Side: Content Bio Details -->
							<div class="ag__popup-right">
								<h3 class="ag__popup-bio-title">About</h3>
								
								<div class="ag__popup-details">
									<?php if ($name) : ?>
										<p><strong>Stage Name:</strong> <?php echo esc_html($name); ?></p>
									<?php endif; ?>
									<?php if ($real_name) : ?>
										<p><strong>Real Name:</strong> <?php echo esc_html($real_name); ?></p>
									<?php endif; ?>
									<?php if ($dob) : ?>
										<p><strong>DOB:</strong> <?php echo esc_html($dob); ?></p>
									<?php endif; ?>
									<?php if ($genre) : ?>
										<p><strong>Genre:</strong> <?php echo esc_html($genre); ?></p>
									<?php endif; ?>
								</div>
								
								<?php if ($overview) : ?>
									<div class="ag__popup-overview">
										<p><strong>Overview:</strong></p>
										<?php echo wpautop(esc_html($overview)); ?>
									</div>
								<?php endif; ?>
								
								<!-- Signed By Section -->
								<div class="ag__popup-signed">
									<span class="ag__popup-signed-label">Signed By</span>
									<div class="ag__popup-signed-logo">
										<?php if ($label_preset === 'dao') : ?>
											<img src="https://cdn.prod.website-files.com/67ad79a8d67e61058839d6fb/69b23d5b710a5d1e7f92e95b_DAO%20Music%20Ent%20White.svg" alt="DAO Music Ent" class="ag__label-logo" />
										<?php elseif ($label_preset === 'maidao') : ?>
											<img src="https://cdn.prod.website-files.com/67ad79a8d67e61058839d6fb/69ba23fc7c8e2137f16dd8bc_MaiDao%20White.svg" alt="MaiDao" class="ag__label-logo" />
										<?php elseif ($label_preset === 'publishing') : ?>
											<img src="https://cdn.prod.website-files.com/67ad79a8d67e61058839d6fb/69c9f685e96b4b101721802f_Property%201%3DPUBLISHING.svg" alt="Publishing" class="ag__label-logo" />
										<?php elseif ($label_preset === 'custom' && $custom_logo) : ?>
											<img src="<?php echo esc_url($custom_logo); ?>" alt="Label Logo" class="ag__label-logo" />
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
