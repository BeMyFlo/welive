<?php

/**
 * AI Zippy Child Theme Functions
 *
 * Project-specific customizations live here.
 * The parent theme (ai-zippy) handles core assets, REST APIs, cart/checkout, etc.
 * This file adds the child's own Vite-built assets and auto-registers any
 * client-specific Gutenberg blocks from assets/blocks/.
 */

defined('ABSPATH') || exit;

// =============================================================================
// Vite manifest reader — child's own assets/dist/.vite/manifest.json
// Mirrors the parent's AiZippy\Core\ViteAssets but scoped to this theme.
// =============================================================================

/**
 * Read the child theme's Vite manifest (cached per-request).
 */
function ai_zippy_child_vite_manifest(): array
{
    static $manifest = null;
    if ($manifest !== null) {
        return $manifest;
    }

    $path = get_stylesheet_directory() . '/assets/dist/.vite/manifest.json';
    if (!file_exists($path)) {
        return $manifest = [];
    }

    $decoded = json_decode(file_get_contents($path), true);
    return $manifest = is_array($decoded) ? $decoded : [];
}

/**
 * Enqueue a child-theme Vite asset by its source entry key.
 * Entry keys are the full path from repo root, e.g.:
 *   src/wp-content/themes/ai-zippy-child/src/js/child.js
 */
function ai_zippy_child_enqueue_vite(string $handle, string $entry, array $js_deps = []): void
{
    $manifest = ai_zippy_child_vite_manifest();
    if (empty($manifest[$entry])) {
        return;
    }

    $asset    = $manifest[$entry];
    $dist_uri = get_stylesheet_directory_uri() . '/assets/dist';
    $dist_dir = get_stylesheet_directory() . '/assets/dist';

    // JS entry — skip stub files emitted by Vite when an SCSS entry has no content yet
    if (!empty($asset['file']) && str_ends_with($asset['file'], '.js')) {
        $file_path = $dist_dir . '/' . $asset['file'];
        $is_scss_entry = !empty($asset['src']) && str_ends_with($asset['src'], '.scss');
        $is_tiny_stub  = file_exists($file_path) && filesize($file_path) < 100;

        if (!($is_scss_entry && $is_tiny_stub)) {
            $version = file_exists($file_path) ? filemtime($file_path) : false;
            wp_enqueue_script($handle, $dist_uri . '/' . $asset['file'], $js_deps, $version, true);
        }
    }

    // CSS-only entry (manifest file is .css directly)
    if (!empty($asset['file']) && str_ends_with($asset['file'], '.css') && empty($asset['css'])) {
        $file_path = $dist_dir . '/' . $asset['file'];
        $version   = file_exists($file_path) ? filemtime($file_path) : false;
        // Depend on the parent theme's style so child CSS loads after it.
        wp_enqueue_style($handle, $dist_uri . '/' . $asset['file'], ['ai-zippy-theme-css-0'], $version);
    }

    // CSS bundled with JS entry
    if (!empty($asset['css'])) {
        foreach ($asset['css'] as $i => $css_file) {
            $file_path = $dist_dir . '/' . $css_file;
            $version   = file_exists($file_path) ? filemtime($file_path) : false;
            wp_enqueue_style(
                $handle . '-css-' . $i,
                $dist_uri . '/' . $css_file,
                ['ai-zippy-theme-css-0'],
                $version
            );
        }
    }
}

/**
 * Mark child theme scripts as ES modules (Vite outputs ESM).
 */
add_filter('script_loader_tag', function (string $tag, string $handle): string {
    if (str_starts_with($handle, 'ai-zippy-child')) {
        return str_replace(' src=', ' type="module" src=', $tag);
    }
    return $tag;
}, 10, 2);

/**
 * Enqueue child theme assets (after parent so overrides work).
 */
add_action('wp_enqueue_scripts', function (): void {
    $base = 'src/wp-content/themes/ai-zippy-child/src';

    ai_zippy_child_enqueue_vite('ai-zippy-child',       $base . '/js/child.js');
    ai_zippy_child_enqueue_vite('ai-zippy-child-style', $base . '/scss/style.scss');
}, 20);

// Floating Zalo chat button + its settings page (Zippy AI → Nút Zalo)
require_once get_stylesheet_directory() . '/inc/zalo-button.php';

// 45-day plan page sign-up form handler
require_once get_stylesheet_directory() . '/inc/plan-join.php';

// =============================================================================
// Auto-register child theme blocks (wp-scripts build output)
// =============================================================================

// Register "WeLive" block category at the top of the inserter
add_filter('block_categories_all', function (array $categories): array {
    return array_merge(
        [
            [
                'slug'  => 'welive',
                'title' => 'WeLive',
                'icon'  => null,
            ],
        ],
        $categories
    );
}, 10, 1);

add_action('init', function (): void {
    $blocks_dir = get_stylesheet_directory() . '/assets/blocks';
    if (!is_dir($blocks_dir)) {
        return;
    }
    foreach (glob($blocks_dir . '/*/block.json') as $block_json) {
        register_block_type(dirname($block_json));
    }
});


// =============================================================================
// Contact Form Submit Handler (AJAX)
// =============================================================================

add_action('wp_ajax_submit_contact_form', 'ai_zippy_child_handle_contact_submit');
add_action('wp_ajax_nopriv_submit_contact_form', 'ai_zippy_child_handle_contact_submit');

function ai_zippy_child_handle_contact_submit(): void
{
    $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email   = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    if (empty($email) || empty($message)) {
        wp_send_json_error(['message' => 'Email and message are required fields.']);
    }

    $to      = get_option('admin_email');
    $subject = 'New Contact Form Inquiry from ' . $name;
    
    $body  = "You have received a new inquiry from the contact form:\n\n";
    $body .= "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Phone: " . $phone . "\n\n";
    $body .= "Message:\n" . $message . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];

    $sent = wp_mail($to, $subject, $body, $headers);

    if ($sent) {
        wp_send_json_success(['message' => 'Thank you! Your submission has been received!']);
    } else {
        wp_send_json_error(['message' => 'Oops! Something went wrong. Please try again.']);
    }
}


// =============================================================================
// Auto-create Coming Soon page & Subscribe Form AJAX Handler
// =============================================================================

add_action('init', function (): void {
    // Check if the coming-soon page exists
    $page = get_page_by_path('coming-soon');
    if (!$page) {
        $page_id = wp_insert_post([
            'post_title'     => 'Coming Soon',
            'post_name'      => 'coming-soon',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_content'   => '<!-- wp:paragraph -->
<p>We are working hard to prepare our site for launch. Stay tuned!</p>
<!-- /wp:paragraph -->',
        ]);
        if ($page_id && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', 'page-coming-soon');
        }
    } else {
        // Ensure the correct template is assigned
        $current_template = get_post_meta($page->ID, '_wp_page_template', true);
        if ($current_template !== 'page-coming-soon') {
            update_post_meta($page->ID, '_wp_page_template', 'page-coming-soon');
        }
    }
});

add_action('wp_ajax_submit_subscribe_form', 'ai_zippy_child_handle_subscribe_submit');
add_action('wp_ajax_nopriv_submit_subscribe_form', 'ai_zippy_child_handle_subscribe_submit');

function ai_zippy_child_handle_subscribe_submit(): void
{
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

    if (empty($email) || !is_email($email)) {
        wp_send_json_error(['message' => 'Please enter a valid email address.']);
    }

    // Save subscriber in options list
    $subscribers = get_option('zippy_coming_soon_subscribers', []);
    if (!is_array($subscribers)) {
        $subscribers = [];
    }
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('zippy_coming_soon_subscribers', $subscribers);
    }

    // Send email to admin
    $to      = get_option('admin_email');
    $subject = 'New Coming Soon Subscriber';
    $body    = "You have a new subscriber for the Coming Soon page:\n\nEmail: " . $email . "\n";
    $headers = ['Content-Type: text/plain; charset=UTF-8'];
    wp_mail($to, $subject, $body, $headers);

    wp_send_json_success(['message' => 'Thank you! You have been subscribed.']);
}




// -----------------------------------------------------------------------------
// Migrations: every file in /migrations runs once, in filename order.
// Applied file names are stored in the `ai_zippy_child_migrations` option.
// A migration file returns a callable and must be idempotent and ADD-only
// (never overwrite content that editors changed on a live site).
// Name files `YYYY_MM_DD_NNN_description.php` so they sort chronologically.
// -----------------------------------------------------------------------------
function ai_zippy_child_run_migrations(): void
{
	$files = glob(get_stylesheet_directory() . '/migrations/*.php');
	if (!$files) {
		return;
	}
	sort($files);

	$done = get_option('ai_zippy_child_migrations', []);
	$done = is_array($done) ? $done : [];

	$pending = array_filter($files, static fn(string $file): bool => !in_array(basename($file), $done, true));
	if (!$pending || get_transient('ai_zippy_child_migrating')) {
		return;
	}

	set_transient('ai_zippy_child_migrating', 1, 60); // avoid two requests running the same migration
	foreach ($pending as $file) {
		try {
			$run = require $file;
			if (is_callable($run)) {
				$run();
			}
			$done[] = basename($file);
			update_option('ai_zippy_child_migrations', $done, false);
		} catch (\Throwable $e) {
			error_log('[ai-zippy-child] migration failed: ' . basename($file) . ' — ' . $e->getMessage());
			break; // stop here; retried on the next request
		}
	}
	delete_transient('ai_zippy_child_migrating');
}
add_action('init', 'ai_zippy_child_run_migrations', 20);

// -----------------------------------------------------------------------------
// Demo images: the Home migration imports photos from migrations/assets/home into the
// Media Library. If the database was copied to another site (e.g. local -> live) the
// attachments exist but the files do not, so re-copy any missing file from the theme.
// -----------------------------------------------------------------------------
function ai_zippy_child_restore_demo_images(): void
{
	$key = 'az_demo_imgs_' . md5(home_url()); // per-domain, so a copied DB re-checks on the new site
	if (get_transient($key)) {
		return;
	}

	$ids = get_posts([
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'meta_key'    => '_az_demo_src',
		'fields'      => 'ids',
		'numberposts' => -1,
	]);
	foreach ($ids as $id) {
		$file = get_attached_file($id);
		if (!$file || file_exists($file)) {
			continue;
		}
		$src = get_stylesheet_directory() . '/migrations/assets/home/' . basename((string) get_post_meta($id, '_az_demo_src', true));
		if (!is_readable($src)) {
			continue;
		}
		wp_mkdir_p(dirname($file));
		if (copy($src, $file)) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $file));
		}
	}
	set_transient($key, 1, HOUR_IN_SECONDS);
}
add_action('init', 'ai_zippy_child_restore_demo_images', 30);

// Overlay header (transparent over the hero) on every page that uses the full-width template.
add_filter('body_class', function (array $classes): array {
	if (is_page_template('page-fullwidth') || is_page_template('page-fullwidth.html')) {
		$classes[] = 'az-overlay-header';
	}
	return $classes;
});
