<?php

defined('ABSPATH') || exit;

$title       = $attributes['title'] ?? 'OUR MILESTONES';
$description = $attributes['description'] ?? '';
$milestones  = $attributes['milestones'] ?? [];

$wrapper = get_block_wrapper_attributes(['class' => 'abm']);
?>
<div <?php echo $wrapper; ?>>
	<div class="abm__container">
		<?php if ($title || $description) : ?>
			<div class="abm__sidebar reveal-on-scroll reveal-fade-up">
				<?php if ($title) : ?>
					<h2 class="abm__heading"><?php echo esc_html($title); ?></h2>
				<?php endif; ?>
				<?php if ($description) : ?>
					<p class="abm__desc"><?php echo wp_kses_post(nl2br($description)); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- Stats strip -->
		<div class="abm__stats reveal-on-scroll reveal-fade-up" data-reveal-delay="150">
			<?php foreach ($milestones as $m) :
				$year   = $m['year'] ?? '';
				$label  = $m['label'] ?? '';
				$detail = $m['detail'] ?? '';
				$date   = $m['date'] ?? '';
				$caption = trim(implode(' · ', array_filter([$year, $detail])));
			?>
				<div class="abm__stat">
					<span class="abm__stat-value"><?php echo esc_html($label); ?></span>
					<?php if ($caption) : ?>
						<span class="abm__stat-caption"><?php echo esc_html($caption); ?></span>
					<?php endif; ?>
					<?php if ($date) : ?>
						<span class="abm__stat-date"><?php echo esc_html($date); ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
