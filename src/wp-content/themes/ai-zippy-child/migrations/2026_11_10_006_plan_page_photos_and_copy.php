<?php

// 45-day plan page (slug "ke-hoach-45-ngay"): fill in the client's photos and updated copy.
//  - Benefits cards: 4 photos. Roadmap tabs: panel copy + photo. Wall and community: creator photos.
//  - FAQ: the client's updated answers.
// Only attributes the page has not set itself are filled, so edits made in the editor are kept.
// Photos come from migrations/assets/home and are sideloaded into this site's Media Library.


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

	$registry = \WP_Block_Type_Registry::get_instance();
	$default  = static function (string $block, string $attr) use ($registry) {
		$type = $registry->get_registered('ai-zippy-child/' . $block);
		return $type && isset($type->attributes[$attr]['default']) ? $type->attributes[$attr]['default'] : null;
	};
	$withImage = static function (array $item, array $image): array {
		return $image + $item;
	};

	$benefit = [$img('plan-benefit-1.jpg'), $img('plan-benefit-2.jpg'), $img('plan-benefit-3.jpg'), $img('plan-benefit-4.jpg')];
	$training = $img('home-roadmap-training.jpg');
	$creators = ['LÊ HÒA' => $img('creator-le-hoa.jpg'), 'THÚY DIỆU' => $img('creator-thuy-dieu.jpg'), 'HNGLE' => $img('creator-hngle.jpg')];

	$blocks  = parse_blocks($page->post_content);
	$changed = false;

	foreach ($blocks as &$b) {
		$name  = $b['blockName'] ?? '';
		$attrs = $b['attrs'] ?? [];

		if ($name === 'ai-zippy-child/creator-benefits' && !isset($attrs['cards'])) {
			$cards = $default('creator-benefits', 'cards');
			if (is_array($cards)) {
				foreach ($cards as $i => $card) {
					$cards[$i] = $withImage($card, $benefit[$i] ?? ['imageId' => 0, 'imageUrl' => '']);
				}
				$attrs['cards'] = $cards;
			}
		} elseif ($name === 'ai-zippy-child/creator-roadmap' && !isset($attrs['tabs'])) {
			$copy = [
				['Bắt đầu từ những điều cơ bản', ['Thống nhất lịch và nội dung LIVE cùng manager.', 'Hoàn thiện hồ sơ, góc quay, ánh sáng và âm thanh.', 'Tập chào hỏi, phản hồi và cảm ơn người xem.']],
				['Ghép kỹ năng thành một phiên LIVE', ['Chuẩn bị khung mở đầu, nội dung chính và kết thúc.', 'Rèn thói quen tự kiểm tra, giữ lịch và làm video hỗ trợ.', 'Cùng manager tổng kết ngày 7, chốt trọng tâm tuần tiếp theo.']],
				['Từng bước tự vận hành', ['Đọc số liệu, chọn một vấn đề và thử một điều chỉnh.', 'Luyện tương tác, PK khi phù hợp và xây dựng cộng đồng.', 'Tự thực hiện một phiên, tổng kết ngày 14 và thống nhất kế hoạch tiếp.']],
				['Phát triển theo mục tiêu của bạn', ['Cùng WELIVE xác định mục tiêu và phần cần rèn thêm.', 'Soạn kế hoạch phù hợp với khả năng, lịch và nội dung của bạn.', 'Kế hoạch cụ thể được thống nhất riêng cho chặng tiếp theo.']],
			];
			$tabs = $default('creator-roadmap', 'tabs');
			if (is_array($tabs)) {
				foreach ($tabs as $i => $tab) {
					if (isset($copy[$i])) {
						$tab['heading'] = $copy[$i][0];
						$tab['bullets'] = $copy[$i][1];
					}
					$tabs[$i] = $withImage($tab, $training);
				}
				$attrs['tabs'] = $tabs;
			}
		} elseif ($name === 'ai-zippy-child/creator-wall' && !isset($attrs['items'])) {
			$items = $default('creator-wall', 'items');
			if (is_array($items)) {
				foreach ($items as $i => $item) {
					$items[$i] = $withImage($item, $creators[$item['name'] ?? ''] ?? ['imageId' => 0, 'imageUrl' => '']);
				}
				$attrs['items'] = $items;
			}
		} elseif ($name === 'ai-zippy-child/creator-community' && !isset($attrs['photos'])) {
			$order = array_values($creators);
			$attrs['photos']  = [$order[0], $order[1], $order[2]];
			$attrs['avatars'] = [$order[0], $order[1]];
		} elseif ($name === 'ai-zippy-child/creator-faq' && !isset($attrs['items'])) {
			$attrs['items'] = [
				['q' => 'Lộ trình 45 ngày có giống nhau cho mọi người?', 'a' => '14 ngày đầu tập trung vào các kỹ năng nền tảng. Kế hoạch tiếp theo được soạn phù hợp với mục tiêu, khả năng và lịch thực tế của từng nhà sáng tạo.'],
				['q' => 'Tôi mới bắt đầu LIVE thì học từ đâu?', 'a' => 'Bạn bắt đầu từ hồ sơ, setup, lịch và nội dung LIVE; tập chào hỏi, phản hồi, cảm ơn người xem, rồi từng bước ghép kỹ năng thành một phiên hoàn chỉnh.'],
				['q' => 'Hỗ trợ tiếp cận LIVE, video và PK triển khai thế nào?', 'a' => 'Đội ngũ trao đổi nhu cầu, chuẩn bị nội dung và xác nhận phần hỗ trợ phù hợp. PK được sắp xếp theo mức độ sẵn sàng và phong cách của nhà sáng tạo.'],
				['q' => 'Tôi cần chuẩn bị gì để trao đổi với WELIVE?', 'a' => 'Chia sẻ thông tin kênh, mục tiêu của bạn và cách liên hệ. Đây là cơ sở để đội ngũ trao đổi và đề xuất kế hoạch phù hợp.'],
			];
		} else {
			continue;
		}
		$b['attrs'] = $attrs;
		$changed = true;
	}
	unset($b);

	if ($changed) {
		wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash(serialize_blocks($blocks))]);
	}
};
