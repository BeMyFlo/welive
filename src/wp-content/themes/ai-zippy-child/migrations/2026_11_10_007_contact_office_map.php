<?php

// Contact page: add the Google Maps embed under the office strip and use the client's updated
// address and directions link. Only values still equal to the earlier defaults are replaced,
// so anything edited in the editor is kept.


return static function (): void {
	$page = get_page_by_path('contact');
	if (!$page || strpos($page->post_content, 'wp:ai-zippy-child/contact-office') === false) {
		return;
	}

	$old_address = '44 Đường D, Khu đô thị, Bình Trưng, TP. Hồ Chí Minh';
	$old_link    = 'https://www.google.com/maps/search/?api=1&query=44%20%C4%90%C6%B0%E1%BB%9Dng%20D%2C%20B%C3%ACnh%20Tr%C6%B0ng%2C%20H%E1%BB%93%20Ch%C3%AD%20Minh';

	$blocks  = parse_blocks($page->post_content);
	$changed = false;

	foreach ($blocks as &$b) {
		if (($b['blockName'] ?? '') !== 'ai-zippy-child/contact-office') {
			continue;
		}
		$attrs = $b['attrs'] ?? [];
		if (isset($attrs['mapUrl'])) {
			continue;
		}
		if (($attrs['address'] ?? $old_address) === $old_address) {
			$attrs['address'] = '44 Đường D, Lakeview City, phường Bình Trưng, TP. Hồ Chí Minh';
		}
		if (($attrs['linkUrl'] ?? $old_link) === $old_link) {
			$attrs['linkUrl'] = 'https://www.google.com/maps/dir/?api=1&destination=44%20%C4%90.%20D%20Khu%20%C4%91%C3%B4%20th%E1%BB%8B%20B%C3%ACnh%20Tr%C6%B0ng%20H%E1%BB%93%20Ch%C3%AD%20Minh%20700000%2C%20Vi%E1%BB%87t%20Nam';
		}
		$attrs['mapUrl']   = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.2350467599754!2d106.76942037481828!3d10.79330148935649!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317526879fd9899f%3A0xfcc3e98c44076a3b!2zNDQgxJAuIEQsIEtodSDEkcO0IHRo4buLLCBCw6xuaCBUcsawbmcsIEjhu5MgQ2jDrSBNaW5oIDcwMDAwMCwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2sus!4v1785547297491!5m2!1svi!2sus';
		$attrs['mapTitle'] = 'Bản đồ văn phòng Welive ở Lakeview City';
		$b['attrs'] = $attrs;
		$changed = true;
	}
	unset($b);

	if ($changed) {
		wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash(serialize_blocks($blocks))]);
	}
};
