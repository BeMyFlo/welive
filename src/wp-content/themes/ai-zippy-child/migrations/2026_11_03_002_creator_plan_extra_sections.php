<?php

// Appends the wall of fame, community and FAQ blocks to the "Kế hoạch 45 ngày" page.
// Add-only: a block that is already on the page is not added again.

defined('ABSPATH') || exit;

return static function (): void {
	$page = get_page_by_path('ke-hoach-45-ngay');
	if (!$page) {
		return;
	}

	$content = $page->post_content;
	foreach (['creator-wall', 'creator-community', 'creator-faq'] as $name) {
		if (strpos($content, 'wp:ai-zippy-child/' . $name) === false) {
			$content = rtrim($content) . "\n\n<!-- wp:ai-zippy-child/" . $name . ' /-->';
		}
	}

	if ($content !== $page->post_content) {
		wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($content)]);
	}
};
