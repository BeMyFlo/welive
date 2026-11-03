<?php

// Adds the "Kế hoạch 45 ngày" page (benefits + roadmap) and puts the creator intro block on Home.
// Add-only: skips anything that already exists.

defined('ABSPATH') || exit;

return static function (): void {
	if (!get_page_by_path('ke-hoach-45-ngay')) {
		wp_insert_post([
			'post_title'    => 'Kế hoạch 45 ngày',
			'post_name'     => 'ke-hoach-45-ngay',
			'post_type'     => 'page',
			'post_status'   => 'publish',
			'post_content'  => "<!-- wp:ai-zippy-child/creator-benefits /-->\n\n<!-- wp:ai-zippy-child/creator-roadmap /-->",
			'page_template' => 'page-fullwidth',
		]);
	}

	$home = get_page_by_path('home');
	if ($home && strpos($home->post_content, 'wp:ai-zippy-child/creator-intro') === false) {
		$block = '<!-- wp:ai-zippy-child/creator-intro /-->';
		$count = 0;
		// Insert right after the hero slider; if there is none, put it first.
		$content = preg_replace(
			'/(<!-- wp:ai-zippy-child\/home-hero-slider.*?\/-->)/s',
			'$1' . "\n\n" . $block,
			$home->post_content,
			1,
			$count
		);
		if (!$count) {
			$content = $block . "\n\n" . $home->post_content;
		}
		wp_update_post(['ID' => $home->ID, 'post_content' => wp_slash($content)]);
	}
};
