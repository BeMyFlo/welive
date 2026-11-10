<?php

/**
 * Floating Zalo chat button (bottom-right on every front-end page).
 *
 * Settings live under Zippy AI → Nút Zalo (falls back to Settings → Nút Zalo
 * if the parent menu is missing). Option: ai_zippy_child_zalo.
 */

defined('ABSPATH') || exit;

const AI_ZIPPY_ZALO_OPTION  = 'ai_zippy_child_zalo';
const AI_ZIPPY_ZALO_DEFAULT = 'https://zalo.me/2311145967487700822';

/**
 * Saved settings merged with defaults.
 *
 * @return array{enabled:bool,link:string,phone:string,label:string}
 */
function ai_zippy_child_zalo_settings(): array
{
    $saved = get_option(AI_ZIPPY_ZALO_OPTION, []);
    $saved = is_array($saved) ? $saved : [];

    return [
        'enabled' => array_key_exists('enabled', $saved) ? (bool) $saved['enabled'] : true,
        'link'    => (string) ($saved['link'] ?? ''),
        'phone'   => (string) ($saved['phone'] ?? ''),
        'label'   => (string) ($saved['label'] ?? 'Chat Zalo'),
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
    $label = $s['label'] !== '' ? $s['label'] : 'Chat Zalo';
    ?>
    <a class="az-zalo" href="<?php echo esc_url(ai_zippy_child_zalo_url($s)); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($label); ?>">
        <span class="az-zalo__tip"><?php echo esc_html($label); ?></span>
        <svg class="az-zalo__icon" viewBox="0 0 48 48" width="28" height="28" aria-hidden="true" focusable="false">
            <text x="24" y="31" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="17" font-weight="800" fill="#fff" letter-spacing="-0.5">Zalo</text>
        </svg>
    </a>
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
            ];
        },
    ]);
});

add_action('admin_menu', function (): void {
    $parent = class_exists('\AiZippy\Admin\ThemeOptions') ? \AiZippy\Admin\ThemeOptions::SLUG : '';
    $title  = 'Nút Zalo';

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
    $opt = AI_ZIPPY_ZALO_OPTION;
    ?>
    <div class="wrap">
        <h1>Nút Zalo</h1>
        <p>Nút chat Zalo nhỏ ở góc phải dưới của mọi trang. Nhập link Zalo <em>hoặc</em> số điện thoại; nếu nhập cả hai thì dùng link.</p>
        <form method="post" action="options.php">
            <?php settings_fields('ai_zippy_child_zalo_group'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Hiển thị nút</th>
                    <td><label><input type="checkbox" name="<?php echo esc_attr($opt); ?>[enabled]" value="1" <?php checked($s['enabled']); ?>> Bật nút Zalo trên website</label></td>
                </tr>
                <tr>
                    <th scope="row"><label for="az-zalo-link">Link Zalo</label></th>
                    <td>
                        <input id="az-zalo-link" type="url" class="regular-text" name="<?php echo esc_attr($opt); ?>[link]" value="<?php echo esc_attr($s['link']); ?>" placeholder="<?php echo esc_attr(AI_ZIPPY_ZALO_DEFAULT); ?>">
                        <p class="description">Link Zalo OA, nhóm hoặc trang cá nhân (vd <code>https://zalo.me/…</code>).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="az-zalo-phone">Số điện thoại Zalo</label></th>
                    <td>
                        <input id="az-zalo-phone" type="tel" class="regular-text" name="<?php echo esc_attr($opt); ?>[phone]" value="<?php echo esc_attr($s['phone']); ?>" placeholder="0901 234 567">
                        <p class="description">Dùng khi để trống ô link. Nút sẽ mở <code>zalo.me/số-điện-thoại</code>.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="az-zalo-label">Chữ gợi ý</label></th>
                    <td><input id="az-zalo-label" type="text" class="regular-text" name="<?php echo esc_attr($opt); ?>[label]" value="<?php echo esc_attr($s['label']); ?>" placeholder="Chat Zalo"></td>
                </tr>
            </table>
            <p class="description">Để trống cả link và số điện thoại thì nút dùng link Zalo Welive mặc định: <?php echo esc_html(AI_ZIPPY_ZALO_DEFAULT); ?></p>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
