<?php

/**
 * 45-day plan page: sign-up form handler (admin-ajax action submit_plan_join).
 * Emails the site admin; honeypot + per-IP throttle against spam.
 */

defined('ABSPATH') || exit;

add_action('wp_ajax_submit_plan_join', 'ai_zippy_child_handle_plan_join');
add_action('wp_ajax_nopriv_submit_plan_join', 'ai_zippy_child_handle_plan_join');

function ai_zippy_child_handle_plan_join(): void
{
    // Honeypot: real visitors never fill this field.
    if (!empty($_POST['website'])) {
        wp_send_json_success();
    }

    $ip  = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
    $key = 'az_plan_join_' . md5($ip);
    if ($ip !== '' && get_transient($key)) {
        wp_send_json_error(['message' => 'Bạn vừa gửi xong, vui lòng đợi một chút rồi thử lại.']);
    }

    $name    = isset($_POST['creator_name']) ? sanitize_text_field(wp_unslash($_POST['creator_name'])) : '';
    $contact = isset($_POST['creator_contact']) ? sanitize_text_field(wp_unslash($_POST['creator_contact'])) : '';
    $channel = isset($_POST['creator_channel']) ? sanitize_text_field(wp_unslash($_POST['creator_channel'])) : '';
    $goal    = isset($_POST['creator_goal']) ? sanitize_textarea_field(wp_unslash($_POST['creator_goal'])) : '';

    if ($name === '' || $contact === '' || $channel === '' || $goal === '') {
        wp_send_json_error(['message' => 'Vui lòng điền đủ các ô bắt buộc.']);
    }

    $body  = "Có nhà sáng tạo đăng ký nhận kế hoạch 45 ngày:\n\n";
    $body .= "Họ và tên: {$name}\n";
    $body .= "Số điện thoại / Zalo: {$contact}\n";
    $body .= "TikTok: {$channel}\n\n";
    $body .= "Mục tiêu:\n{$goal}\n";

    $sent = wp_mail(
        get_option('admin_email'),
        'Đăng ký kế hoạch 45 ngày từ ' . $name,
        $body,
        ['Content-Type: text/plain; charset=UTF-8']
    );

    if (!$sent) {
        wp_send_json_error(['message' => 'Chưa gửi được, bạn thử lại hoặc nhắn WELIVE qua Zalo nhé.']);
    }

    if ($ip !== '') {
        set_transient($key, 1, 30);
    }
    wp_send_json_success();
}
