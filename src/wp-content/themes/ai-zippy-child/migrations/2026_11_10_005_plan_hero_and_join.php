<?php

// 45-day plan page (slug "ke-hoach-45-ngay"): add the client's new hero at the top and the sign-up form
// at the bottom. Existing blocks stay untouched. Each addition is skipped if the page already has it.
// The hero photo comes from migrations/assets/home and is sideloaded into this site's Media Library.


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

	$page = get_page_by_path('ke-hoach-45-ngay');
	if (!$page) {
		return;
	}

	$content  = $page->post_content;
	$has_hero = strpos($content, 'wp:ai-zippy-child/plan-hero') !== false;
	$has_join = strpos($content, 'wp:ai-zippy-child/plan-join') !== false;
	if ($has_hero && $has_join) {
		return;
	}

	$block = static fn(string $name, array $attrs): string => '<!-- wp:ai-zippy-child/' . $name . ' ' . serialize_block_attributes($attrs) . ' /-->';

	if (!$has_hero) {
		$photo   = $img('plan-hero.jpg');
		$content = $block('plan-hero', [
			'image' => $photo + ['alt' => 'Nhà sáng tạo trong studio — ảnh minh họa', 'position' => '50% 40%'],
			'align' => 'full',
		]) . "\n\n" . ltrim($content);
	}
	if (!$has_join) {
		$content = rtrim($content) . "\n\n" . $block('plan-join', ['align' => 'full']);
	}

	wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($content)]);
};
