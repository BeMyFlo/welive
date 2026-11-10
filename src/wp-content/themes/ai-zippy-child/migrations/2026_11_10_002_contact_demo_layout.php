<?php

// Rebuilds the Contact page (slug "contact") to match the client's black-theme demo:
// photo banner, intro + Zalo phone card, three QR channel cards, office strip.
// Photos come from migrations/assets/home and are sideloaded into this site's Media Library.
// The previous content (info cards + inquiry form) is kept as a draft page ("contact-cu").
// Skips itself if the page already uses contact-banner.


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

	$page = get_page_by_path('contact');
	if (!$page || strpos($page->post_content, 'wp:ai-zippy-child/contact-banner') !== false) {
		return;
	}

	$studio = $img('contact-studio.jpg');
	$oa     = $img('contact-zalo-oa.jpg');

	$banner = $block('contact-banner', [
		'image'       => $studio + ['alt' => '', 'position' => '54% 48%'],
		'title'       => 'Liên hệ',
		'titleAccent' => 'Welive.',
		'btnText'     => 'Thông tin liên hệ',
		'btnUrl'      => '#thong-tin-lien-he',
		'align'       => 'full',
	]);

	$intro = $block('contact-intro', [
		'anchor'           => 'thong-tin-lien-he',
		'text'             => 'Bạn muốn gia nhập, cần hỗ trợ livestream hoặc có ý tưởng hợp tác? Chọn kênh phù hợp để kết nối với Welive.',
		'phoneTitle'       => 'Số điện thoại Zalo Welive',
		'phone'            => '',
		'phonePlaceholder' => 'Đang cập nhật',
		'phoneNote'        => 'Dùng số này để tìm Welive trên Zalo.',
		'align'            => 'full',
	]);

	$channels = $block('contact-channels', [
		'title'           => 'Kênh kết nối',
		'subtitle'        => 'Quét mã QR để mở kênh trên điện thoại.',
		'placeholderText' => 'Chờ mã QR',
		'verifiedLabel'   => 'Đã xác thực trên Zalo',
		'items'           => [
			['tone' => 'featured', 'verified' => true, 'title' => 'Zalo OA Welive', 'text' => 'Theo dõi thông báo từ Welive và nhắn tin qua tài khoản Zalo chính thức.', 'alt' => 'Mã QR Zalo OA Welive Media'] + $oa,
			['tone' => 'community', 'verified' => false, 'title' => 'Cộng đồng nhà sáng tạo Welive', 'text' => 'Tham gia nhóm Zalo để gặp gỡ và trao đổi kinh nghiệm LIVE cùng các nhà sáng tạo.', 'alt' => 'Mã QR nhóm Zalo Cộng đồng nhà sáng tạo Welive', 'imageId' => 0, 'imageUrl' => ''],
			['tone' => 'plain', 'verified' => false, 'title' => 'TikTok Welive', 'text' => 'Theo dõi trang TikTok chính thức của Welive để xem video và cập nhật hoạt động.', 'alt' => 'Mã QR trang TikTok chính thức của Welive', 'imageId' => 0, 'imageUrl' => ''],
		],
		'align'           => 'full',
	]);

	$office = $block('contact-office', [
		'title'    => 'Văn phòng Welive',
		'address'  => '44 Đường D, Khu đô thị, Bình Trưng, TP. Hồ Chí Minh',
		'linkText' => 'Xem chỉ đường',
		'linkUrl'  => 'https://www.google.com/maps/search/?api=1&query=44%20%C4%90%C6%B0%E1%BB%9Dng%20D%2C%20B%C3%ACnh%20Tr%C6%B0ng%2C%20H%E1%BB%93%20Ch%C3%AD%20Minh',
		'align'    => 'full',
	]);

	if (!get_page_by_path('contact-cu')) {
		wp_insert_post([
			'post_title'    => 'Contact (cũ)',
			'post_name'     => 'contact-cu',
			'post_type'     => 'page',
			'post_status'   => 'draft',
			'post_content'  => wp_slash($page->post_content),
			'page_template' => get_page_template_slug($page->ID),
		]);
	}

	wp_update_post([
		'ID'           => $page->ID,
		'post_content' => wp_slash(implode("\n\n", [$banner, $intro, $channels, $office])),
	]);
};
