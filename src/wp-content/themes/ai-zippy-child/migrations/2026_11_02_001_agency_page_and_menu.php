<?php

// Adds the Agency page and its menu link if they are missing.

defined('ABSPATH') || exit;

return static function (): void {
	$page = get_page_by_path('agency');
	if (!$page) {
		$page_id = wp_insert_post([
			'post_title'    => 'Agency',
			'post_name'     => 'agency',
			'post_type'     => 'page',
			'post_status'   => 'publish',
			'post_content'  => "<!-- wp:ai-zippy-child/agency-hero /-->\n\n<!-- wp:ai-zippy-child/agency-audience /-->\n\n<!-- wp:ai-zippy-child/agency-services /-->\n\n<!-- wp:ai-zippy-child/agency-contact /-->",
			'page_template' => 'page-fullwidth',
		]);
		$page = $page_id && !is_wp_error($page_id) ? get_post($page_id) : null;
	}

	$navs = get_posts(['post_type' => 'wp_navigation', 'post_status' => 'publish', 'numberposts' => 1, 'orderby' => 'ID', 'order' => 'ASC']);
	if ($page && $navs) {
		$nav = $navs[0];
		$url = get_permalink($page);
		if (strpos($nav->post_content, '"id":' . $page->ID . ',') === false && strpos($nav->post_content, $url) === false) {
			$link = '<!-- wp:navigation-link ' . wp_json_encode([
				'label' => 'Agency',
				'type'  => 'page',
				'id'    => $page->ID,
				'url'   => $url,
				'kind'  => 'post-type',
			], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ' /-->';
			$parts = array_filter(explode("\n\n", trim($nav->post_content)), 'strlen');
			$out   = [];
			$added = false;
			foreach ($parts as $part) {
				if (!$added && stripos($part, '"label":"Contact"') !== false) {
					$out[] = $link;
					$added = true;
				}
				$out[] = $part;
			}
			if (!$added) {
				$out[] = $link;
			}
			wp_update_post(['ID' => $nav->ID, 'post_content' => implode("\n\n", $out)]);
		}
	}
};
