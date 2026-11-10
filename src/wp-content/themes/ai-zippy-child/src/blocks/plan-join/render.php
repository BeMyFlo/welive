<?php
defined('ABSPATH') || exit;

$a       = $attributes;
$anchor  = $a['anchor'] ?? '';
$accent  = $a['titleAccent'] ?? '';
$uid     = 'pjn-' . wp_unique_id();
$wrapper = get_block_wrapper_attributes(['class' => 'pjn'] + ($anchor ? ['id' => $anchor] : []));
?>
<section <?php echo $wrapper; ?>>
	<div class="pjn__wrap">
		<div class="pjn__copy">
			<?php if (!empty($a['pill'])) : ?>
				<span class="pjn__pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/><path d="M20 2v4M22 4h-4"/><circle cx="4" cy="20" r="2"/></svg><?php echo wp_kses_post($a['pill']); ?></span>
			<?php endif; ?>
			<h2 class="pjn__title"><?php echo wp_kses_post($a['title'] ?? ''); ?><?php if ($accent !== '') : ?><br><span><?php echo wp_kses_post($accent); ?></span><?php endif; ?></h2>
			<?php if (!empty($a['text'])) : ?><p class="pjn__text"><?php echo wp_kses_post($a['text']); ?></p><?php endif; ?>
			<?php if (!empty($a['note'])) : ?><p class="pjn__note"><?php echo wp_kses_post($a['note']); ?></p><?php endif; ?>
		</div>
		<form class="pjn__form" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" data-success="<?php echo esc_attr(wp_strip_all_tags($a['successText'] ?? '')); ?>" data-error="<?php echo esc_attr(wp_strip_all_tags($a['errorText'] ?? '')); ?>">
			<input type="hidden" name="action" value="submit_plan_join" />
			<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="pjn__hp" aria-hidden="true" />
			<label for="<?php echo esc_attr($uid); ?>-name"><?php echo wp_kses_post($a['labelName'] ?? ''); ?> <span>*</span></label>
			<input id="<?php echo esc_attr($uid); ?>-name" name="creator_name" autocomplete="name" placeholder="<?php echo esc_attr($a['placeholderName'] ?? ''); ?>" required />
			<div class="pjn__row">
				<div>
					<label for="<?php echo esc_attr($uid); ?>-contact"><?php echo wp_kses_post($a['labelContact'] ?? ''); ?> <span>*</span></label>
					<input id="<?php echo esc_attr($uid); ?>-contact" name="creator_contact" type="tel" autocomplete="tel" placeholder="<?php echo esc_attr($a['placeholderContact'] ?? ''); ?>" required />
				</div>
				<div>
					<label for="<?php echo esc_attr($uid); ?>-channel"><?php echo wp_kses_post($a['labelChannel'] ?? ''); ?> <span>*</span></label>
					<input id="<?php echo esc_attr($uid); ?>-channel" name="creator_channel" placeholder="<?php echo esc_attr($a['placeholderChannel'] ?? ''); ?>" required />
				</div>
			</div>
			<label for="<?php echo esc_attr($uid); ?>-goal"><?php echo wp_kses_post($a['labelGoal'] ?? ''); ?> <span>*</span></label>
			<textarea id="<?php echo esc_attr($uid); ?>-goal" name="creator_goal" maxlength="2000" placeholder="<?php echo esc_attr($a['placeholderGoal'] ?? ''); ?>" required></textarea>
			<button class="pjn__submit" type="submit"><?php echo esc_html($a['btnText'] ?? ''); ?></button>
			<?php if (!empty($a['formNote'])) : ?><p class="pjn__form-note"><?php echo wp_kses_post($a['formNote']); ?></p><?php endif; ?>
			<div class="pjn__feedback" role="status" hidden></div>
		</form>
	</div>
</section>
