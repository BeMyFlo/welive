<?php

// Rebuilds the About Welive page to match the client's black-theme demo.
// Same approach as the Home migration: photos ship in the theme, are sideloaded into this site's
// Media Library, the old content is kept as a draft page, then the new layout is written.
// Skips itself if the page already contains the about-hero-services block.

defined('ABSPATH') || exit;

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

	$block = static fn(string $name, array $attrs): string => '<!-- wp:ai-zippy-child/' . $name . ' ' . serialize_block_attributes($attrs) . ' /-->';

	$page = get_page_by_path('about-welive');
	if (!$page || strpos($page->post_content, 'wp:ai-zippy-child/about-hero-services') !== false) {
		return;
	}

	$hero = $block('about-hero-services', [
		'services' => [
			['tag' => '01 / START', 'title' => 'Vận hành từ số 0', 'text' => 'Lập kế hoạch và xây dựng nền tảng cho nhà sáng tạo.', 'alt' => 'Góc làm việc với micro và màn hình hỗ trợ vận hành livestream'] + $img('studio-operations.png'),
			['tag' => '02 / GROW', 'title' => 'Phân tích & tăng trưởng', 'text' => 'Theo dõi số liệu, tối ưu nội dung và cải thiện doanh thu.', 'alt' => 'Hệ thống màn hình và thiết bị dựng nội dung livestream'] + $img('studio-growth.png'),
			['tag' => '03 / PROTECT', 'title' => 'Bảo vệ tài khoản', 'text' => 'Đồng hành, hỗ trợ và bảo vệ tài khoản nhà sáng tạo.', 'alt' => 'Máy quay trong không gian studio livestream'] + $img('studio-support.png'),
		],
		'align'    => 'full',
	]);

	$beliefs = $block('about-beliefs', $img('team.jpg') + ['align' => 'full']);

	$history = $block('about-history', ['align' => 'full']);

	$g = static fn(string $file, string $title, string $cat, string $label, string $desc): array => ['title' => $title, 'label' => $label, 'category' => $cat, 'description' => $desc] + $img($file);
	$gallery = $block('about-gallery', [
		'items' => [
			$g('tiktok-livefest.jpg', 'Trên sân khấu TikTok LIVE Fest', 'tiktok', 'TIKTOK LIVE', 'Khoảnh khắc tại TikTok LIVE Fest từ thư viện ảnh Welive.'),
			$g('about-bigo-awards-2024.jpg', 'Dấu ấn BIGO Awards 2024', 'bigo', 'BIGO LIVE', 'Khoảnh khắc tại sự kiện BIGO Awards 2024.'),
			$g('about-tiktok-team.jpg', 'Một lần gặp gỡ cùng TikTok', 'tiktok', 'TIKTOK', 'Các thành viên trong một hoạt động tại TikTok.'),
			$g('about-bigo-event-2023.jpg', 'Khoảnh khắc tại sự kiện BIGO', 'bigo', 'BIGO LIVE', 'Hình ảnh từ sự kiện BIGO trong thư viện Welive.'),
			$g('about-creator-stage.jpg', 'Cùng nhau trước ánh đèn', 'together', 'CÙNG WELIVE', 'Các nhà sáng tạo cùng nhau tại một sự kiện.'),
			$g('about-livefest-checkin.jpg', 'Có mặt tại TikTok LIVE Fest', 'tiktok', 'TIKTOK LIVE', 'Khoảnh khắc check-in tại TikTok LIVE Fest.'),
			$g('about-training.jpg', 'Cùng học, cùng tiến bộ', 'together', 'CÙNG WELIVE', 'Một buổi trao đổi và đào tạo của đội ngũ.'),
			$g('about-bigo-team.jpg', 'Gặp gỡ và kết nối cùng BIGO', 'bigo', 'BIGO LIVE', 'Các thành viên trong không gian sự kiện BIGO.'),
			$g('about-livefest-gift.jpg', 'Mang về một chút kỷ niệm', 'tiktok', 'TIKTOK LIVE', 'Một kỷ niệm cùng TikTok LIVE Fest.'),
			$g('about-creator-community.jpg', 'Những gương mặt thân quen', 'together', 'CÙNG WELIVE', 'Các nhà sáng tạo gặp gỡ và lưu lại khoảnh khắc cùng nhau.'),
			$g('team.jpg', 'Những con người làm nên Welive', 'together', 'CÙNG WELIVE', 'Hình ảnh từ cộng đồng nhà sáng tạo Welive.'),
		],
		'align' => 'full',
	]);

	$community = $block('about-community', ['alt' => 'Đội ngũ làm việc tại văn phòng Welive'] + $img('about-creator.png') + ['align' => 'full']);

	if (!get_page_by_path('about-welive-cu')) {
		wp_insert_post([
			'post_title'    => 'About Welive (cũ)',
			'post_name'     => 'about-welive-cu',
			'post_type'     => 'page',
			'post_status'   => 'draft',
			'post_content'  => wp_slash($page->post_content),
			'page_template' => get_page_template_slug($page->ID),
		]);
	}

	wp_update_post([
		'ID'           => $page->ID,
		'post_content' => wp_slash(implode("\n\n", [$hero, $beliefs, $history, $gallery, $community])),
	]);
};
