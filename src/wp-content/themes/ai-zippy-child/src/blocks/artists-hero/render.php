<?php
$text_align = $attributes['textAlign'] ?? 'left';


defined('ABSPATH') || exit;

$title   = $attributes['title'] ?? 'OUR ARTISTS';
$wrapper = get_block_wrapper_attributes(['class' => 'ah has-text-align-' . $text_align]);
?>
<div <?php echo $wrapper; ?>>
	<div class="ah__container">
		<h2 class="ah__title"><?php echo esc_html($title); ?></h2>
	</div>
</div>
