<?php

// Rebuilds the Agency page (slug "agency") to match the client's black-theme demo:
// split hero, two path cards, three-step "Gói kết nối" section, partnership photo, orange Zalo band.
// Photos come from migrations/assets/home and are sideloaded into this site's Media Library.
// The previous content is kept as a draft page ("agency-cu"). Skips itself if the page already uses agency-cta.


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

	$page = get_page_by_path('agency');
	if (!$page || strpos($page->post_content, 'wp:ai-zippy-child/agency-cta') !== false) {
		return;
	}

	$studio   = $img('agency-studio-live.jpg');
	$training = $img('agency-training.jpg');

	$hero = $block('agency-hero', [
		'layout'      => 'split',
		'eyebrow'     => 'Giải pháp Agency',
		'title'       => 'Phát triển đội ngũ',
		'titleAccent' => 'nhà sáng tạo LIVE.',
		'description' => 'Dù bạn đang mở rộng một agency hay chuẩn bị xây dựng đội ngũ đầu tiên, Welive đồng hành theo nhu cầu và giai đoạn phát triển của bạn.',
		'image'       => $studio + ['alt' => 'Nhà sáng tạo đang livestream tại studio với micro, màn hình và đèn quay', 'position' => '50% 55%'],
		'images'      => [],
		'align'       => 'full',
	]);

	$audience = $block('agency-audience', [
		'cards' => [
			[
				'tone'     => 'light',
				'eyebrow'  => 'Dành cho agency đang hoạt động',
				'title'    => 'Mở rộng đội ngũ Idol LIVE',
				'text'     => 'Welive giúp agency kết nối với những Idol LIVE phù hợp với định hướng nội dung, tiêu chí và số lượng bạn cần bổ sung. Bạn có thể chọn gói kết nối theo nhu cầu phát triển của đội ngũ hiện tại.',
				'subtitle' => '',
				'btnText'  => 'Tìm hiểu các gói kết nối',
				'btnUrl'   => '#ket-noi-idol',
				'imageId'  => 0,
				'imageUrl' => '',
			],
			[
				'tone'     => 'orange',
				'eyebrow'  => 'Dành cho đối tác muốn bắt đầu',
				'title'    => 'Hợp tác xây dựng agency',
				'text'     => 'Nếu bạn muốn phát triển một agency của riêng mình, Welive sẵn sàng hợp tác từ những bước đầu. Hai bên cùng định hình đội ngũ nhà sáng tạo, thống nhất cách phối hợp và chuẩn bị để đưa hoạt động LIVE vào vận hành.',
				'subtitle' => '',
				'btnText'  => 'Tìm hiểu cách hợp tác',
				'btnUrl'   => '#hop-tac-agency',
				'imageId'  => 0,
				'imageUrl' => '',
			],
		],
		'align' => 'full',
	]);

	$step = static fn(string $title, string $text): array => [
		'icon' => 'search', 'title' => $title, 'subtitle' => '', 'text' => $text, 'imageId' => 0, 'imageUrl' => '',
	];
	$services = $block('agency-services', [
		'layout'      => 'steps',
		'anchor'      => 'ket-noi-idol',
		'title'       => 'Gói kết nối Idol LIVE',
		'description' => 'Mỗi gói bắt đầu từ nhu cầu thực tế của agency. Bạn cho Welive biết mình cần thêm bao nhiêu nhà sáng tạo, muốn phát triển nội dung gì và ưu tiên những tiêu chí nào.',
		'cards'       => [
			$step('Thống nhất nhu cầu', 'Hai bên trao đổi về số lượng cần bổ sung, phong cách LIVE và các tiêu chí phù hợp với đội ngũ của bạn.'),
			$step('Tìm kiếm và sàng lọc', 'Welive tìm kiếm và xem xét hồ sơ Idol LIVE dựa trên những tiêu chí đã thống nhất.'),
			$step('Giới thiệu và kết nối', 'Welive giới thiệu những gương mặt phù hợp để agency trao đổi trực tiếp và đánh giá khả năng đồng hành.'),
		],
		'btnText'     => 'Trao đổi về gói kết nối',
		'btnUrl'      => '#lien-he',
		'align'       => 'full',
	]);

	$partner = $block('agency-partner', [
		'anchor'  => 'hop-tac-agency',
		'image'   => $training + ['alt' => 'Buổi đào tạo của đội ngũ Welive', 'position' => '50% 50%'],
		'title'   => 'Bắt đầu agency cùng Welive',
		'text'    => 'Buổi trao đổi đầu tiên tập trung vào điều bạn muốn xây dựng, từ nhóm nhà sáng tạo bạn muốn phát triển đến cách tổ chức hoạt động LIVE trong giai đoạn đầu.',
		'text2'   => 'Từ đó, hai bên bàn về hình thức hợp tác, vai trò của mỗi bên và những việc cần chuẩn bị để đội ngũ đầu tiên có thể đi vào hoạt động.',
		'btnText' => 'Trao đổi cơ hội hợp tác',
		'btnUrl'  => '#lien-he',
		'align'   => 'full',
	]);

	$cta = $block('agency-cta', [
		'anchor'  => 'lien-he',
		'title'   => 'Trao đổi cùng Welive',
		'text'    => 'Chia sẻ với Welive về đội ngũ bạn muốn mở rộng hoặc agency bạn đang ấp ủ. Hai bên sẽ cùng trao đổi bước tiếp theo.',
		'btnText' => 'Nhắn Welive qua Zalo',
		'btnUrl'  => 'https://zalo.me/2311145967487700822',
		'newTab'  => true,
		'align'   => 'full',
	]);

	if (!get_page_by_path('agency-cu')) {
		wp_insert_post([
			'post_title'    => 'Agency (cũ)',
			'post_name'     => 'agency-cu',
			'post_type'     => 'page',
			'post_status'   => 'draft',
			'post_content'  => wp_slash($page->post_content),
			'page_template' => get_page_template_slug($page->ID),
		]);
	}

	wp_update_post([
		'ID'           => $page->ID,
		'post_content' => wp_slash(implode("\n\n", [$hero, $audience, $services, $partner, $cta])),
	]);
};
