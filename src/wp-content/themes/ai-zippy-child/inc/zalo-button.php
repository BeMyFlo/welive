<?php

/**
 * Floating contact dock on the right edge of every front-end page: Call, TikTok and Zalo.
 *
 * Settings live under Zippy AI → Thanh liên hệ (falls back to Settings → Thanh liên hệ
 * if the parent menu is missing). Option: ai_zippy_child_zalo.
 * A tab is shown only when it has a value; Zalo falls back to the default Welive link.
 */

defined('ABSPATH') || exit;

const AI_ZIPPY_ZALO_OPTION  = 'ai_zippy_child_zalo';
const AI_ZIPPY_ZALO_DEFAULT = 'https://zalo.me/2311145967487700822';

/**
 * Saved settings merged with defaults.
 *
 * @return array<string,mixed>
 */
function ai_zippy_child_zalo_settings(): array
{
    $saved = get_option(AI_ZIPPY_ZALO_OPTION, []);
    $saved = is_array($saved) ? $saved : [];

    return [
        'enabled' => array_key_exists('enabled', $saved) ? (bool) $saved['enabled'] : true,
        'link'    => (string) ($saved['link'] ?? ''),
        'phone'   => (string) ($saved['phone'] ?? ''),
        'label'   => (string) ($saved['label'] ?? 'Zalo'),
        'call'    => (string) ($saved['call'] ?? ''),
        'call_label'   => (string) ($saved['call_label'] ?? 'Gọi điện'),
        'tiktok'       => (string) ($saved['tiktok'] ?? ''),
        'tiktok_label' => (string) ($saved['tiktok_label'] ?? 'TikTok'),
    ];
}

/**
 * Final chat URL: full link wins, then phone number, then the default Welive link.
 */
function ai_zippy_child_zalo_url(array $s): string
{
    if ($s['link'] !== '') {
        return $s['link'];
    }

    $digits = preg_replace('/[^\d+]/', '', $s['phone']);
    if ($digits !== '') {
        return 'https://zalo.me/' . ltrim($digits, '+');
    }

    return AI_ZIPPY_ZALO_DEFAULT;
}

// -----------------------------------------------------------------------------
// Front end
// -----------------------------------------------------------------------------

add_action('wp_enqueue_scripts', function (): void {
    if (!ai_zippy_child_zalo_settings()['enabled']) {
        return;
    }
    $file = get_stylesheet_directory() . '/assets/css/zalo-button.css';
    wp_enqueue_style(
        'ai-zippy-child-zalo',
        get_stylesheet_directory_uri() . '/assets/css/zalo-button.css',
        [],
        file_exists($file) ? filemtime($file) : null
    );
}, 25);

add_action('wp_footer', function (): void {
    $s = ai_zippy_child_zalo_settings();
    if (!$s['enabled']) {
        return;
    }

    $items = [];

    $dial = preg_replace('/[^\d+]/', '', $s['call']);
    if ($dial !== '') {
        $items[] = [
            'class' => 'call',
            'href'  => 'tel:' . $dial,
            'label' => $s['call_label'] !== '' ? $s['call_label'] : 'Gọi điện',
            'blank' => false,
            'icon'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        ];
    }

    if ($s['tiktok'] !== '') {
        $items[] = [
            'class' => 'tiktok',
            'href'  => $s['tiktok'],
            'label' => $s['tiktok_label'] !== '' ? $s['tiktok_label'] : 'TikTok',
            'blank' => true,
            'icon'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 0 1-2.59 2.5 2.6 2.6 0 0 1-2.6-2.6 2.6 2.6 0 0 1 2.6-2.6c.27 0 .53.04.77.12V9.66a5.7 5.7 0 0 0-.77-.05A5.69 5.69 0 0 0 4.17 15.3a5.69 5.69 0 0 0 5.69 5.7 5.69 5.69 0 0 0 5.69-5.7V9.01a7.35 7.35 0 0 0 4.28 1.37V7.3a4.3 4.3 0 0 1-3.23-1.48z"/></svg>',
        ];
    }

    $items[] = [
        'class' => 'zalo',
        'href'  => ai_zippy_child_zalo_url($s),
        'label' => $s['label'] !== '' ? $s['label'] : 'Zalo',
        'blank' => true,
        'icon'  => '<svg viewBox="0 0 48 48" width="26" height="26" aria-hidden="true" focusable="false"><text x="24" y="31" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="17" font-weight="800" fill="#fff" letter-spacing="-0.5">Zalo</text></svg>',
    ];
    ?>
    <nav class="az-dock" aria-label="Liên hệ nhanh">
        <?php foreach ($items as $it) : ?>
            <a class="az-dock__item az-dock__item--<?php echo esc_attr($it['class']); ?>" href="<?php echo esc_url($it['href'], ['http', 'https', 'tel']); ?>"<?php echo $it['blank'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                <span class="az-dock__icon"><?php echo $it['icon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
                <span class="az-dock__label"><?php echo esc_html($it['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <?php
}, 30);

// -----------------------------------------------------------------------------
// Admin settings page
// -----------------------------------------------------------------------------

add_action('admin_init', function (): void {
    register_setting('ai_zippy_child_zalo_group', AI_ZIPPY_ZALO_OPTION, [
        'type'              => 'array',
        'default'           => [],
        'sanitize_callback' => function ($input): array {
            $input = is_array($input) ? $input : [];

            return [
                'enabled' => !empty($input['enabled']),
                'link'    => esc_url_raw(trim((string) ($input['link'] ?? ''))),
                'phone'   => preg_replace('/[^\d+\s().-]/', '', (string) ($input['phone'] ?? '')),
                'label'   => sanitize_text_field((string) ($input['label'] ?? '')),
                'call'    => preg_replace('/[^\d+\s().-]/', '', (string) ($input['call'] ?? '')),
                'call_label'   => sanitize_text_field((string) ($input['call_label'] ?? '')),
                'tiktok'       => esc_url_raw(trim((string) ($input['tiktok'] ?? ''))),
                'tiktok_label' => sanitize_text_field((string) ($input['tiktok_label'] ?? '')),
            ];
        },
    ]);
});

add_action('admin_menu', function (): void {
    $parent = class_exists('\AiZippy\Admin\ThemeOptions') ? \AiZippy\Admin\ThemeOptions::SLUG : '';
    $title  = 'Thanh liên hệ';

    if ($parent !== '') {
        add_submenu_page($parent, $title, $title, 'manage_options', 'ai-zippy-child-zalo', 'ai_zippy_child_zalo_render_page');
    } else {
        add_options_page($title, $title, 'manage_options', 'ai-zippy-child-zalo', 'ai_zippy_child_zalo_render_page');
    }
}, 30);

function ai_zippy_child_zalo_render_page(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $s   = ai_zippy_child_zalo_settings();
    $opt = esc_attr(AI_ZIPPY_ZALO_OPTION);
    $row = static function (string $id, string $label, string $key, string $value, string $type = 'text', string $placeholder = '', string $help = '') use ($opt): void {
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label></th>
            <td>
                <input id="<?php echo esc_attr($id); ?>" type="<?php echo esc_attr($type); ?>" class="regular-text" name="<?php echo $opt; ?>[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr($placeholder); ?>">
                <?php if ($help !== '') : ?><p class="description"><?php echo wp_kses_post($help); ?></p><?php endif; ?>
            </td>
        </tr>
        <?php
    };
    ?>
    <div class="wrap">
        <h1>Thanh liên hệ</h1>
        <p>Thanh nhỏ ở cạnh phải của mọi trang với các nút Gọi điện, TikTok và Zalo. Nút nào để trống thì không hiện (riêng Zalo luôn hiện, mặc định dùng link Zalo Welive).</p>
        <form method="post" action="options.php">
            <?php settings_fields('ai_zippy_child_zalo_group'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Hiển thị thanh</th>
                    <td><label><input type="checkbox" name="<?php echo $opt; ?>[enabled]" value="1" <?php checked($s['enabled']); ?>> Bật thanh liên hệ trên website</label></td>
                </tr>
                <?php
                $row('az-call', 'Số điện thoại gọi', 'call', $s['call'], 'tel', '0901 234 567', 'Bấm vào sẽ gọi thẳng trên điện thoại.');
                $row('az-call-label', 'Chữ dưới nút gọi', 'call_label', $s['call_label'], 'text', 'Gọi điện');
                $row('az-tiktok', 'Link TikTok', 'tiktok', $s['tiktok'], 'url', 'https://www.tiktok.com/@welive', 'Link trang TikTok chính thức của Welive.');
                $row('az-tiktok-label', 'Chữ dưới nút TikTok', 'tiktok_label', $s['tiktok_label'], 'text', 'TikTok');
                $row('az-zalo-link', 'Link Zalo', 'link', $s['link'], 'url', AI_ZIPPY_ZALO_DEFAULT, 'Link Zalo OA, nhóm hoặc trang cá nhân (vd <code>https://zalo.me/…</code>).');
                $row('az-zalo-phone', 'Số điện thoại Zalo', 'phone', $s['phone'], 'tel', '0901 234 567', 'Dùng khi để trống ô link: nút sẽ mở <code>zalo.me/số-điện-thoại</code>.');
                $row('az-zalo-label', 'Chữ dưới nút Zalo', 'label', $s['label'], 'text', 'Zalo');
                ?>
            </table>
            <p class="description">Để trống cả link và số Zalo thì nút Zalo dùng link mặc định: <?php echo esc_html(AI_ZIPPY_ZALO_DEFAULT); ?></p>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
