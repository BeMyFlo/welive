<?php

// Rebuilds the Creator page (slug "artists") to match the client's black-theme demo:
// Wall of Honor (artists-spotlight, layout "wall") + two story rows + join CTA.
// Photos come from migrations/assets/home and are sideloaded into this site's Media Library.
// The previous content is kept as a draft page. Skips itself if the page already uses creator-stories.

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

	$page = get_page_by_path('artists');
	if (!$page || strpos($page->post_content, 'wp:ai-zippy-child/creator-stories') !== false) {
		return;
	}

	// Bios and social links already typed on the old page, keyed by lower-case name.
	$old = [];
	foreach (parse_blocks($page->post_content) as $b) {
		if (($b['blockName'] ?? '') === 'ai-zippy-child/artists-spotlight') {
			foreach (($b['attrs']['artists'] ?? []) as $a) {
				$old[mb_strtolower(trim((string) ($a['name'] ?? '')))] = $a;
			}
		}
	}

	// name, photo file (or ''), object-position, fallback bio, fallback socials
	$people = [
		['ARIC', 'creator-aric.jpg', '50% 42%', 'Giọng hát trầm ấm, mộc mạc và giàu cảm xúc tạo nên màu sắc riêng của Aric trong những phiên LIVE.', ['tiktok' => 'https://www.tiktok.com/@aricc147', 'facebook' => 'https://www.facebook.com/aric147']],
		['METI', '', '', '', []],
		['BEANH', '', '', '', []],
		['HNGLE', 'creator-hngle.jpg', '51% 43%', 'Theo đuổi sáng tác và biểu diễn, Hngle dùng âm nhạc để kể chuyện, truyền tải cảm xúc và kết nối với khán giả trẻ.', ['tiktok' => 'https://www.tiktok.com/@hungleee2408', 'instagram' => 'https://www.instagram.com/vps.hngle/']],
		['MISTI', '', '', '', []],
		['THOA', '', '', '', []],
		['LÊ HÒA', 'creator-le-hoa.jpg', '49% 40%', 'Lê Hòa kể chuyện bằng âm nhạc, giọng hát và khả năng chơi nhạc trực tiếp, tạo nên sự kết nối gần gũi với người xem.', ['tiktok' => 'https://www.tiktok.com/@lehoa9436']],
		['THÚY DIỆU', 'creator-thuy-dieu.jpg', '50% 40%', '', []],
		['LUNA', '', '', '', []],
		['SUNU', '', '', '', []],
	];

	$socials_of = static function (array $o, array $fallback): array {
		$s = array_merge(['facebook' => '', 'instagram' => '', 'tiktok' => '', 'threads' => '', 'youtube' => ''], $fallback);
		foreach (($o['socials'] ?? []) as $k => $v) {
			if ($v !== '') {
				$s[$k] = $v;
			}
		}
		return $s;
	};

	$artists = [];
	foreach ($people as $i => [$name, $file, $pos, $bio, $fallback]) {
		$o = $old[mb_strtolower($name)] ?? [];
		$artists[] = [
			'name'     => $name,
			'number'   => str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
			'dob'      => '',
			'genre'    => '',
			'bio'      => !empty($o['bio']) ? $o['bio'] : $bio,
			'position' => $pos,
			'socials'  => $socials_of($o, $fallback),
		] + ($file ? $img($file) : ['imageId' => 0, 'imageUrl' => '']);
	}

	$wall = $block('artists-spotlight', [
		'layout'      => 'wall',
		'title'       => 'Bức tường',
		'titleAccent' => 'danh vọng.',
		'artists'     => $artists,
		'align'       => 'full',
	]);

	$story = static function (int $index, string $linkText = 'Xem câu chuyện') use ($artists): array {
		$a = $artists[$index];
		return [
			'name'      => $a['name'],
			'linkText'  => $linkText,
			'imageId'   => $a['imageId'],
			'imageUrl'  => $a['imageUrl'],
			'position'  => $a['position'] ?: '50% 40%',
			'bio'       => $a['bio'],
			'tiktok'    => $a['socials']['tiktok'],
			'facebook'  => $a['socials']['facebook'],
			'instagram' => $a['socials']['instagram'],
		];
	};

	$row1 = $block('creator-stories', [
		'items'        => [$story(6), $story(7), $story(3)], // Lê Hòa, Thúy Diệu, Hngle
		'pendingCount' => 2,
		'isLast'       => false,
		'align'        => 'full',
	]);
	$row2 = $block('creator-stories', [
		'eyebrow'      => '',
		'rowTitle'     => 'Nghệ sĩ và nhà sáng tạo LIVE',
		'items'        => [$story(0)], // Aric
		'pendingCount' => 4,
		'isLast'       => true,
		'align'        => 'full',
	]);
	$join = $block('creator-join', ['align' => 'full']);

	if (!get_page_by_path('artists-cu')) {
		wp_insert_post([
			'post_title'    => 'Artists (cũ)',
			'post_name'     => 'artists-cu',
			'post_type'     => 'page',
			'post_status'   => 'draft',
			'post_content'  => wp_slash($page->post_content),
			'page_template' => get_page_template_slug($page->ID),
		]);
	}

	wp_update_post([
		'ID'           => $page->ID,
		'post_content' => wp_slash(implode("\n\n", [$wall, $row1, $row2, $join])),
	]);
};
