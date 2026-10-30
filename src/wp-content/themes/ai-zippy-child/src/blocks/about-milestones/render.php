<?php

defined('ABSPATH') || exit;

$title       = $attributes['title'] ?? 'OUR MILESTONES';
$description = $attributes['description'] ?? '';
$milestones  = $attributes['milestones'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'abm']);
?>
<div <?php echo $wrapper; ?>>
	<div class="abm__container">
		<div class="abm__flex">
			<!-- Header Sidebar -->
			<div class="abm__sidebar reveal-on-scroll reveal-fade-up">
				<h2 class="abm__heading"><?php echo esc_html($title); ?></h2>
				<p class="abm__desc"><?php echo wp_kses_post(nl2br($description)); ?></p>
			</div>

			<!-- Timeline Horizontal Track -->
			<div class="abm__timeline-track" data-reveal-stagger=".abm__milestone-box" data-reveal-delay-base="150">
				<div class="abm__track-line"></div>
				
				<?php foreach ($milestones as $m) : 
					$year   = $m['year'] ?? '';
					$label  = $m['label'] ?? '';
					$detail = $m['detail'] ?? '';
					$date   = $m['date'] ?? '';
				?>
					<div class="abm__milestone-box reveal-item reveal-fade-up">
						<div class="abm__indicator-col">
							<div class="abm__indicator-dot"></div>
							<?php if ($date) : ?>
								<span class="abm__date-badge"><?php echo esc_html($date); ?></span>
							<?php endif; ?>
						</div>
						
						<div class="abm__card-content">
							<span class="abm__year-label"><?php echo esc_html($year); ?></span>
							<div class="abm__card-box">
								<h4 class="abm__card-label"><?php echo esc_html($label); ?></h4>
							</div>
							<h6 class="abm__card-detail"><?php echo esc_html($detail); ?></h6>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
