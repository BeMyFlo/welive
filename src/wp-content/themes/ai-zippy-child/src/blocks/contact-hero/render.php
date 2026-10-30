<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$title   = $attributes['title'] ?? 'Contact';
$wrapper = get_block_wrapper_attributes(['class' => 'ch has-text-align-' . $text_align]);
?>
<div <?php echo $wrapper; ?>>
	<div class="ch__container">
		<h1 class="ch__title"><?php echo esc_html($title); ?></h1>
	</div>
</div>
