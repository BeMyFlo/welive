<?php

defined('ABSPATH') || exit;

$title     = $attributes['title'] ?? 'ECOSYSTEM';
$subtitle  = $attributes['subtitle'] ?? '';
$divisions = $attributes['divisions'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'abec']);
?>
<div <?php echo $wrapper; ?>>
	<div class="abec__container">
		<!-- Header -->
		<div class="abec__header reveal-on-scroll reveal-fade-up">
			<h2 class="abec__title"><?php echo esc_html($title); ?></h2>
			<p class="abec__subtitle"><?php echo wp_kses_post($subtitle); ?></p>
		</div>

		<!-- Interactive Ecosystem Panels -->
		<div class="abec__main reveal-on-scroll reveal-fade-up" data-reveal-delay="150">
			<!-- Tab Menu -->
			<div class="abec__tab-menu">
				<?php foreach ($divisions as $index => $div) : 
					$name = $div['name'] ?? '';
				?>
					<button class="abec__tab-btn <?php echo $index === 0 ? 'abec__tab-btn--active' : ''; ?>" data-index="<?php echo $index; ?>">
						<span class="abec__tab-name"><?php echo esc_html($name); ?></span>
						<span class="abec__tab-arrow">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
					</button>
				<?php endforeach; ?>
			</div>

			<!-- Tab Panels -->
			<div class="abec__tab-content">
				<?php foreach ($divisions as $index => $div) : 
					$desc = $div['description'] ?? '';
				?>
					<div class="abec__tab-panel <?php echo $index === 0 ? 'abec__tab-panel--active' : ''; ?>" data-panel="<?php echo $index; ?>">
						<div class="abec__card">
							<p class="abec__desc"><?php echo wp_kses_post(nl2br($desc)); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
