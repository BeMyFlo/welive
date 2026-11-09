<?php

// Rebuilds the Home page to match the client's black-theme demo.
// - Imports the demo photos from migrations/assets/home into the Media Library of the site it runs on.
// - Backs the previous Home content up to a draft page "Home (cũ)", then writes the new layout.
// Skips itself if Home already contains the home-about block.
// (Live safety net for the image files: ai_zippy_child_restore_demo_images() in functions.php.)

defined('ABSPATH') || exit;

return static function (): void {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$dir = get_stylesheet_directory() . '/migrations/assets/home/';

	// file name => attachment id (reuses an earlier import of the same file).
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

	// A failed import aborts the migration (it is retried on the next request) instead of saving a Home with blank images.
	$img = static function (string $file) use ($import): array {
		$id = $import($file);
		if (!$id) {
			throw new \RuntimeException('could not import ' . $file);
		}
		return ['imageId' => $id, 'imageUrl' => (string) wp_get_attachment_url($id)];
	};

	$block = static fn(string $name, array $attrs): string => '<!-- wp:ai-zippy-child/' . $name . ' ' . serialize_block_attributes($attrs) . ' /-->';

	$home = get_page_by_path('home');
	if (!$home || strpos($home->post_content, 'wp:ai-zippy-child/home-about') !== false) {
		return;
	}

	$hero = $block('home-hero-slider', [
		'kicker'  => 'WELIVE CREATOR NETWORK',
		'ctaText' => 'Khám phá nhà sáng tạo',
		'ctaUrl'  => '/artists/',
		'slides'  => [
			['artistName' => 'Lê Hòa', 'link' => '', 'title' => 'LÊ HÒA', 'subtitle' => '', 'position' => '50% 42%'] + $img('creator-le-hoa.jpg'),
			['artistName' => 'Thúy Diệu', 'link' => '', 'title' => 'THÚY DIỆU', 'subtitle' => '', 'position' => '50% 35%'] + $img('creator-thuy-dieu.jpg'),
			['artistName' => 'Hngle', 'link' => '', 'title' => 'HNGLE', 'subtitle' => '', 'position' => '50% 40%'] + $img('creator-hngle.jpg'),
		],
		'align'   => 'full',
	]);

	$about = $block('home-about', [
		'images' => [
			['alt' => 'Cộng đồng nhà sáng tạo Welive'] + $img('team.jpg'),
			['alt' => 'Welive tại TikTok LIVE Fest'] + $img('tiktok-livefest.jpg'),
			['alt' => 'Không gian hỗ trợ vận hành LIVE'] + $img('studio-operations.png'),
		],
		'align'  => 'full',
	]);

	$roadmap = $block('home-roadmap', ['align' => 'full']);

	$creators = $block('home-creators', [
		'creators' => [
			['name' => 'Lê Hòa', 'cta' => 'Xem câu chuyện', 'url' => '/artists/', 'position' => '50% 45%'] + $img('creator-le-hoa.jpg'),
			['name' => 'Thúy Diệu', 'cta' => 'Xem câu chuyện', 'url' => '/artists/', 'position' => '51% 40%'] + $img('creator-thuy-dieu.jpg'),
			['name' => 'Hngle', 'cta' => 'Xem câu chuyện', 'url' => '/artists/', 'position' => '50% 50%'] + $img('creator-hngle.jpg'),
			['name' => 'Aric', 'cta' => 'Khám phá nhà sáng tạo', 'url' => '/artists/', 'position' => '50% 50%'] + $img('creator-aric.jpg'),
		],
		'align'    => 'full',
	]);

	$agency = $block('home-agency', [
		'cards' => [
			['label' => 'DÀNH CHO AGENCY', 'title' => 'Bạn đang<br>vận hành agency.', 'text' => 'Tìm kiếm nhà sáng tạo phù hợp và mở rộng đội ngũ.', 'url' => ''] + $img('studio-growth.png'),
			['label' => 'BẮT ĐẦU HÀNH TRÌNH MỚI', 'title' => 'Bạn muốn<br>trở thành agency.', 'text' => 'Trao đổi những bước đầu để xây dựng đội ngũ nhà sáng tạo.', 'url' => ''] + $img('studio-support.png'),
		],
		'align' => 'full',
	]);

	$join = $block('home-join-us', [
		'variant'         => 'band',
		'kicker'          => 'KẾT NỐI CÙNG WELIVE',
		'title'           => 'Cùng viết hành trình tiếp theo.',
		'btnText'         => 'Liên hệ Welive',
		'btnUrl'          => '/contact/',
		'textAlign'       => 'left',
		'backgroundColor' => '#ff5000',
		'align'           => 'full',
	]);

	// Keep the old layout as a draft so nothing is lost.
	if (!get_page_by_path('home-cu')) {
		wp_insert_post([
			'post_title'    => 'Home (cũ)',
			'post_name'     => 'home-cu',
			'post_type'     => 'page',
			'post_status'   => 'draft',
			'post_content'  => wp_slash($home->post_content),
			'page_template' => get_page_template_slug($home->ID),
		]);
	}

	wp_update_post([
		'ID'           => $home->ID,
		'post_content' => wp_slash(implode("\n\n", [$hero, $about, $roadmap, $creators, $agency, $join])),
	]);
};
