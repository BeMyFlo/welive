<?php

// Home page: switch to the client's updated design.
//  - Roadmap block: day tabs + detail panel + photo (layout "tabs").
//  - Creators / Agency headers: brown pill with an icon.
// Edits the existing blocks in place (other content stays); each block is skipped if already converted.
// The photo comes from migrations/assets/home and is sideloaded into this site's Media Library.


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

	$photo = $img('home-roadmap-training.jpg');

	$tab = static fn(string $days, string $label, string $title, array $bullets): array => [
		'days' => $days, 'label' => $label, 'panelTitle' => $title, 'bullets' => $bullets,
	];

	$blocks = parse_blocks($page->post_content);
	$changed = false;

	foreach ($blocks as &$b) {
		$name  = $b['blockName'] ?? '';
		$attrs = $b['attrs'] ?? [];

		if ($name === 'ai-zippy-child/home-roadmap' && ($attrs['layout'] ?? 'cards') !== 'tabs') {
			$attrs['layout'] = 'tabs';
			$attrs['intro']  = '14 ngày đầu giúp bạn làm quen, rèn kỹ năng và từng bước tự vận hành LIVE. Chặng tiếp theo được soạn theo mục tiêu, khả năng và lịch của bạn.';
			$attrs['tabs']   = [
				$tab('NGÀY 1–3', 'Làm quen &amp; thiết lập', 'Bắt đầu từ những điều cơ bản', [
					'Thống nhất lịch và nội dung LIVE cùng manager.',
					'Hoàn thiện hồ sơ, góc quay, ánh sáng và âm thanh.',
					'Tập chào hỏi, phản hồi và cảm ơn người xem.',
				]),
				$tab('NGÀY 4–7', 'Giữ nhịp &amp; ghép kỹ năng', 'Ghép kỹ năng thành một phiên LIVE', [
					'Chuẩn bị khung mở đầu, nội dung chính và kết thúc.',
					'Rèn thói quen tự kiểm tra, giữ lịch và làm video hỗ trợ.',
					'Cùng manager tổng kết ngày 7, chốt trọng tâm tuần tiếp theo.',
				]),
				$tab('NGÀY 8–14', 'Rèn luyện &amp; tự vận hành', 'Từng bước tự vận hành', [
					'Đọc số liệu, chọn một vấn đề và thử một điều chỉnh.',
					'Luyện tương tác, PK khi phù hợp và xây dựng cộng đồng.',
					'Tự thực hiện một phiên, tổng kết ngày 14 và thống nhất kế hoạch tiếp.',
				]),
				$tab('NGÀY 15–45', 'Phát triển theo mục tiêu', 'Phát triển theo mục tiêu của bạn', [
					'Cùng WELIVE xác định mục tiêu và phần cần rèn thêm.',
					'Soạn kế hoạch phù hợp với khả năng, lịch và nội dung của bạn.',
					'Kế hoạch cụ thể được thống nhất riêng cho chặng tiếp theo.',
				]),
			];
			$attrs['image']  = $photo + ['alt' => 'Chuẩn bị phiên LIVE — ảnh minh họa'];
			$attrs['note']   = 'Cùng nhìn lại tiến bộ ở ngày 7 và ngày 14.';
			$b['attrs'] = $attrs;
			$changed = true;
		} elseif ($name === 'ai-zippy-child/home-creators' && ($attrs['linkStyle'] ?? 'link') !== 'tag') {
			$attrs['linkStyle'] = 'tag';
			$attrs['linkIcon']  = 'crown';
			$attrs['linkText']  = 'Bức tường danh vọng';
			$b['attrs'] = $attrs;
			$changed = true;
		} elseif ($name === 'ai-zippy-child/home-agency' && ($attrs['linkStyle'] ?? 'link') !== 'tag') {
			$attrs['linkStyle'] = 'tag';
			$attrs['linkIcon']  = 'briefcase';
			$b['attrs'] = $attrs;
			$changed = true;
		}
	}
	unset($b);

	if ($changed) {
		wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash(serialize_blocks($blocks))]);
	}
};
