<?php
/**
 * Plugin Name: Cultural Translate
 * Plugin URI: https://culturaltranslate.com
 * Description: Professional AI-powered translation with cultural context. Automatically translate your WordPress content into 14+ languages with cultural sensitivity.
 * Version: 1.0.0
 * Author: Cultural Translate
 * Author URI: https://culturaltranslate.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cultural-translate
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('CULTURAL_TRANSLATE_VERSION', '1.0.0');
define('CULTURAL_TRANSLATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CULTURAL_TRANSLATE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CULTURAL_TRANSLATE_PLUGIN_FILE', __FILE__);

// Autoload classes
spl_autoload_register(function ($class) {
    if (strpos($class, 'CulturalTranslate\\') === 0) {
        $file = CULTURAL_TRANSLATE_PLUGIN_DIR . 'includes/' . str_replace('\\', '/', substr($class, 18)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Initialize plugin
function cultural_translate_init() {
    // Load text domain
    load_plugin_textdomain('cultural-translate', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize admin if in admin area
    if (is_admin()) {
        new CulturalTranslate\Admin();
    }
    
    // Initialize API client
    $api_client = new CulturalTranslate\API_Client();
    
    // Add auto-translate hooks
    $auto_translate = get_option('cultural_translate_auto_translate', false);
    if ($auto_translate) {
        add_action('save_post', 'cultural_translate_auto_translate_post', 10, 2);
    }
}
add_action('plugins_loaded', 'cultural_translate_init');

/**
 * Auto-translate post on save
 */
function cultural_translate_auto_translate_post($post_id, $post) {
    // Skip auto-saves and revisions
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Only translate published posts
    if ($post->post_status !== 'publish') {
        return;
    }
    
    // Check if post type is enabled for translation
    $enabled_post_types = get_option('cultural_translate_post_types', ['post', 'page']);
    if (!in_array($post->post_type, $enabled_post_types)) {
        return;
    }
    
    // Get target languages
    $target_languages = get_option('cultural_translate_target_languages', []);
    if (empty($target_languages)) {
        return;
    }
    
    // Get API client
    $api_client = new CulturalTranslate\API_Client();
    
    // Translate title and content
    foreach ($target_languages as $lang_code) {
        // Translate title
        $translated_title = $api_client->translate([
            'text' => $post->post_title,
            'target_language' => $lang_code,
            'tone' => get_option('cultural_translate_tone', 'formal')
        ]);
        
        // Translate content
        $translated_content = $api_client->translate([
            'text' => $post->post_content,
            'target_language' => $lang_code,
            'tone' => get_option('cultural_translate_tone', 'formal')
        ]);
        
        // Store translations as post meta
        if ($translated_title) {
            update_post_meta($post_id, "_cultural_translate_title_{$lang_code}", $translated_title['translated_text']);
        }
        
        if ($translated_content) {
            update_post_meta($post_id, "_cultural_translate_content_{$lang_code}", $translated_content['translated_text']);
        }
    }
}

/**
 * Activation hook
 */
function cultural_translate_activate() {
    // Create default options
    add_option('cultural_translate_api_key', '');
    add_option('cultural_translate_api_url', 'https://culturaltranslate.com/api/sandbox/v1');
    add_option('cultural_translate_auto_translate', false);
    add_option('cultural_translate_target_languages', []);
    add_option('cultural_translate_tone', 'formal');
    add_option('cultural_translate_post_types', ['post', 'page']);
    
    // Create custom table for translation cache
    global $wpdb;
    $table_name = $wpdb->prefix . 'cultural_translate_cache';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL,
        language_code varchar(5) NOT NULL,
        field_type varchar(50) NOT NULL,
        original_text longtext NOT NULL,
        translated_text longtext NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY post_id (post_id),
        KEY language_code (language_code)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'cultural_translate_activate');

/**
 * Deactivation hook
 */
function cultural_translate_deactivate() {
    // Clean up scheduled tasks if any
    wp_clear_scheduled_hook('cultural_translate_cron');
}
register_deactivation_hook(__FILE__, 'cultural_translate_deactivate');

/**
 * Add settings link on plugins page
 */
function cultural_translate_settings_link($links) {
    $settings_link = '<a href="admin.php?page=cultural-translate">' . __('Settings', 'cultural-translate') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cultural_translate_settings_link');

/**
 * Enqueue admin scripts
 */
function cultural_translate_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_cultural-translate') {
        return;
    }
    
    wp_enqueue_style(
        'cultural-translate-admin',
        CULTURAL_TRANSLATE_PLUGIN_URL . 'assets/css/admin.css',
        [],
        CULTURAL_TRANSLATE_VERSION
    );
    
    wp_enqueue_script(
        'cultural-translate-admin',
        CULTURAL_TRANSLATE_PLUGIN_URL . 'assets/js/admin.js',
        ['jquery'],
        CULTURAL_TRANSLATE_VERSION,
        true
    );
    
    wp_localize_script('cultural-translate-admin', 'culturalTranslate', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('cultural_translate_nonce'),
        'strings' => [
            'testing' => __('Testing connection...', 'cultural-translate'),
            'success' => __('Connection successful!', 'cultural-translate'),
            'error' => __('Connection failed. Please check your API key.', 'cultural-translate'),
        ]
    ]);
}
add_action('admin_enqueue_scripts', 'cultural_translate_admin_scripts');

/**
 * Add meta box for inline editing
 */
function cultural_translate_add_meta_box() {
    $post_types = get_option('cultural_translate_post_types', ['post', 'page']);
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'cultural_translate_meta_box',
            __('Cultural Translate', 'cultural-translate'),
            'cultural_translate_meta_box_callback',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'cultural_translate_add_meta_box');

/**
 * Meta box callback
 */
function cultural_translate_meta_box_callback($post) {
    wp_nonce_field('cultural_translate_meta_box', 'cultural_translate_meta_box_nonce');
    
    $target_languages = get_option('cultural_translate_target_languages', []);
    
    if (empty($target_languages)) {
        echo '<p>' . __('Please configure target languages in plugin settings.', 'cultural-translate') . '</p>';
        return;
    }
    
    $languages = [
        'ar' => 'Arabic', 'de' => 'German', 'en' => 'English', 'es' => 'Spanish',
        'fr' => 'French', 'hi' => 'Hindi', 'it' => 'Italian', 'ja' => 'Japanese',
        'ko' => 'Korean', 'nl' => 'Dutch', 'pt' => 'Portuguese', 'ru' => 'Russian',
        'tr' => 'Turkish', 'zh' => 'Chinese'
    ];
    
    echo '<div class="cultural-translate-meta-box">';
    echo '<p><strong>' . __('Available Translations:', 'cultural-translate') . '</strong></p>';
    
    foreach ($target_languages as $lang_code) {
        $lang_name = $languages[$lang_code] ?? $lang_code;
        $has_translation = get_post_meta($post->ID, "_cultural_translate_title_{$lang_code}", true);
        
        echo '<div class="translation-status">';
        echo '<span class="dashicons dashicons-' . ($has_translation ? 'yes' : 'no') . '"></span> ';
        echo esc_html($lang_name);
        
        if ($has_translation) {
            echo ' <button type="button" class="button-link view-translation" data-lang="' . esc_attr($lang_code) . '">';
            echo __('View', 'cultural-translate') . '</button>';
        }
        
        echo '</div>';
    }
    
    echo '<p style="margin-top: 15px;">';
    echo '<button type="button" class="button button-primary" id="cultural-translate-now">';
    echo __('Translate Now', 'cultural-translate');
    echo '</button>';
    echo '</p>';
    
    echo '</div>';
}

/**
 * AJAX handler for manual translation
 */
function cultural_translate_ajax_translate() {
    check_ajax_referer('cultural_translate_nonce', 'nonce');
    
    $post_id = intval($_POST['post_id'] ?? 0);
    
    if (!$post_id) {
        wp_send_json_error(['message' => 'Invalid post ID']);
    }
    
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(['message' => 'Post not found']);
    }
    
    cultural_translate_auto_translate_post($post_id, $post);
    
    wp_send_json_success(['message' => 'Translation completed']);
}
add_action('wp_ajax_cultural_translate_translate', 'cultural_translate_ajax_translate');

/**
 * AJAX handler for testing API connection
 */
function cultural_translate_ajax_test_connection() {
    check_ajax_referer('cultural_translate_nonce', 'nonce');
    
    $api_client = new CulturalTranslate\API_Client();
    $result = $api_client->test_connection();
    
    if ($result) {
        wp_send_json_success(['message' => 'Connection successful!']);
    } else {
        wp_send_json_error(['message' => 'Connection failed']);
    }
}
add_action('wp_ajax_cultural_translate_test', 'cultural_translate_ajax_test_connection');
