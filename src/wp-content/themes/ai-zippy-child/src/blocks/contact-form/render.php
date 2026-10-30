<?php

defined('ABSPATH') || exit;

$title      = $attributes['title'] ?? 'Inquiry';
$map_iframe = $attributes['mapIframe'] ?? '';
$wrapper    = get_block_wrapper_attributes(['class' => 'cf']);
?>
<div <?php echo $wrapper; ?>>
	<div class="cf__container">
		<div class="cf__layout">
			<!-- Left side: Form -->
			<div class="cf__form-wrap">
				<h2 class="cf__head"><?php echo esc_html($title); ?></h2>
				
				<form class="cf__form" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post">
					<input type="hidden" name="action" value="submit_contact_form" />
					
					<div class="cf__field-wrapper">
						<label for="cf-name" class="cf__field-label">Name</label>
						<input class="cf__text-field" name="name" placeholder="e.g. John Doe" type="text" id="cf-name" />
					</div>
					
					<div class="cf__field-wrapper">
						<label for="cf-email" class="cf__field-label">Email Address *</label>
						<input class="cf__text-field" name="email" placeholder="e.g. johndoe@email.com" type="email" id="cf-email" required />
					</div>
					
					<div class="cf__field-wrapper">
						<label for="cf-phone" class="cf__field-label">Phone Number</label>
						<input class="cf__text-field" name="phone" placeholder="(123) - 456 - 789" type="tel" id="cf-phone" />
					</div>
					
					<div class="cf__field-wrapper">
						<label for="cf-message" class="cf__field-label">Your Message *</label>
						<textarea class="cf__message-area" name="message" placeholder="Write Your Message Here" maxlength="5000" id="cf-message" required></textarea>
					</div>
					
					<button type="submit" class="cf__button">Submit</button>
				</form>

				<!-- Success / Error messages -->
				<div class="cf__status-msg cf__status-msg--success" style="display:none;"></div>
				<div class="cf__status-msg cf__status-msg--error" style="display:none;"></div>
			</div>

			<!-- Right side: Map -->
			<div class="cf__map-wrap">
				<label class="cf__field-label">Location</label>
				<div class="cf__map">
					<?php 
					if ($map_iframe) {
						// Render iframe
						echo $map_iframe;
					} else {
						echo '<div class="cf__map-placeholder">No map configured.</div>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
