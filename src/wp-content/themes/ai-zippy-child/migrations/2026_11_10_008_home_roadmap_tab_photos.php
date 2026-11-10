<?php

// Home page roadmap tabs: give each tab its own photo (they used to share one).
// Only tabs that have no photo of their own are filled, so photos chosen in the editor are kept.
// Photos come from migrations/assets/home (already in the Media Library from earlier migrations).


return static function (): void {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$dir = get_stylesheet_directory() . '/migrations/assets/home/';

	$import = static function (string $file) use ($dir): int {
		$existing = get_posts([
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_az_demo_src',
			'meta_value'  => $file,
			'fields'      => 'ids',
			'numberposts' => 1,
		]);
		if ($existing) {
			return (int) $existing[0];
		}
		if (!is_readable($dir . $file)) {
			return 0;
		}
		$tmp = wp_tempnam($file);
		copy($dir . $file, $tmp);
		$id = media_handle_sideload(['name' => $file, 'tmp_name' => $tmp], 0, pathinfo($file, PATHINFO_FILENAME));
		if (is_wp_error($id)) {
			@unlink($tmp);
			error_log('[ai-zippy-child] demo image import failed: ' . $file . ' — ' . $id->get_error_message());
			return 0;
		}
		update_post_meta($id, '_az_demo_src', $file);
		return (int) $id;
	};

	// A failed import aborts the migration (retried on the next request) instead of saving blank images.
	$img = static function (string $file) use ($import): array {
		$id = $import($file);
		if (!$id) {
			throw new \RuntimeException('could not import ' . $file);
		}
		return ['imageId' => $id, 'imageUrl' => (string) wp_get_attachment_url($id)];
	};

	$page = get_page_by_path('home');
	if (!$page || strpos($page->post_content, 'wp:ai-zippy-child/home-roadmap') === false) {
		return;
	}

	$photos = [
		['home-roadmap-training.jpg', 'Chuẩn bị phiên LIVE — ảnh minh họa'],
		['studio-operations.png', 'Góc làm việc với micro và màn hình hỗ trợ vận hành livestream'],
		['studio-growth.png', 'Hệ thống màn hình và thiết bị dựng nội dung livestream'],
		['about-training.jpg', 'Buổi đào tạo của đội ngũ Welive'],
	];
	$loaded = [];
	foreach ($photos as [$file, $alt]) {
		$loaded[] = $img($file) + ['alt' => $alt];
	}

	$blocks  = parse_blocks($page->post_content);
	$changed = false;

	foreach ($blocks as &$b) {
		if (($b['blockName'] ?? '') !== 'ai-zippy-child/home-roadmap') {
			continue;
		}
		$attrs = $b['attrs'] ?? [];
		if (($attrs['layout'] ?? '') !== 'tabs' || empty($attrs['tabs']) || !is_array($attrs['tabs'])) {
			continue;
		}
		foreach ($attrs['tabs'] as $i => &$tab) {
			if (empty($tab['imageId']) && empty($tab['imageUrl']) && isset($loaded[$i])) {
				$tab = $loaded[$i] + $tab;
				$changed = true;
			}
		}
		unset($tab);
		$b['attrs'] = $attrs;
	}
	unset($b);

	if ($changed) {
		wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash(serialize_blocks($blocks))]);
	}
};
