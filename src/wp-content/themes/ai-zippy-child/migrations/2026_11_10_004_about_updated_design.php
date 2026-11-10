<?php

// About page: switch to the client's updated design.
//  - Intro block: compact variant ("Về Welive.", dark service cards, brown button, dark stats panel).
//  - Section order: intro, gallery, history, beliefs, community.
//  - Eyebrows of history / beliefs lose their "/ 0x" suffix.
// Edits the existing blocks in place (other content stays); skipped if the intro is already compact.

defined('ABSPATH') || exit;

return static function (): void {
	$page = get_page_by_path('about-welive');
	if (!$page || strpos($page->post_content, 'wp:ai-zippy-child/about-hero-services') === false) {
		return;
	}

	$blocks = parse_blocks($page->post_content);
	$by     = [];
	$rest   = [];
	$changed = false;

	foreach ($blocks as $b) {
		$name = $b['blockName'] ?? '';
		if ($name === '') {
			continue; // blank lines between blocks
		}
		$attrs = $b['attrs'] ?? [];

		if ($name === 'ai-zippy-child/about-hero-services' && ($attrs['variant'] ?? 'classic') !== 'compact') {
			$attrs['variant']     = 'compact';
			$attrs['eyebrow']     = '';
			$attrs['title']       = 'Về';
			$attrs['titleAccent'] = 'Welive.';
			$attrs['description'] = 'Welive đồng hành cùng nhà sáng tạo TikTok LIVE từ những bước đầu, giúp bạn xây dựng nền tảng và phát triển theo mục tiêu của mình.';
			if (!empty($attrs['services']) && is_array($attrs['services'])) {
				foreach ($attrs['services'] as &$svc) {
					$svc['tag'] = '';
				}
				unset($svc);
			}
			$changed = true;
		} elseif ($name === 'ai-zippy-child/about-history' && ($attrs['eyebrow'] ?? 'WELIVE / 02') === 'WELIVE / 02') {
			$attrs['eyebrow'] = 'HÀNH TRÌNH WELIVE';
			$changed = true;
		} elseif ($name === 'ai-zippy-child/about-beliefs' && ($attrs['eyebrow'] ?? 'GIÁ TRỊ CỐT LÕI / 01') === 'GIÁ TRỊ CỐT LÕI / 01') {
			$attrs['eyebrow'] = 'GIÁ TRỊ CỐT LÕI';
			$changed = true;
		}
		$b['attrs'] = $attrs;
		$by[$name][] = $b;
		$rest[] = $b;
	}

	// Desired order; any other block keeps its place after these.
	$order = [
		'ai-zippy-child/about-hero-services',
		'ai-zippy-child/about-gallery',
		'ai-zippy-child/about-history',
		'ai-zippy-child/about-beliefs',
		'ai-zippy-child/about-community',
	];
	$sorted = [];
	foreach ($order as $name) {
		foreach ($by[$name] ?? [] as $b) {
			$sorted[] = $b;
		}
	}
	foreach ($rest as $b) {
		if (!in_array($b['blockName'], $order, true)) {
			$sorted[] = $b;
		}
	}

	if ($sorted !== $rest) {
		$changed = true;
	}
	if ($changed) {
		wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash(implode("\n\n", array_map('serialize_block', $sorted)))]);
	}
};
