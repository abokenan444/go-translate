<?php
/**
 * CulturalTranslate Settings
 */

if (!defined('ABSPATH')) {
    exit;
}

class CulturalTranslate_Settings {
    
    /**
     * Initialize settings
     */
    public static function init() {
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('wp_ajax_culturaltranslate_test_connection', array(__CLASS__, 'ajax_test_connection'));
    }
    
    /**
     * Register settings
     */
    public static function register_settings() {
        register_setting('culturaltranslate_settings', 'culturaltranslate_api_key');
        register_setting('culturaltranslate_settings', 'culturaltranslate_api_url');
        register_setting('culturaltranslate_settings', 'culturaltranslate_default_source_lang');
        register_setting('culturaltranslate_settings', 'culturaltranslate_default_target_lang');
        register_setting('culturaltranslate_settings', 'culturaltranslate_auto_translate');
        register_setting('culturaltranslate_settings', 'culturaltranslate_brand_voice_id');
        register_setting('culturaltranslate_settings', 'culturaltranslate_apply_glossary');
    }
    
    /**
     * Render settings page
     */
    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Handle form submission
        if (isset($_POST['culturaltranslate_settings_submit'])) {
            check_admin_referer('culturaltranslate_settings');
            
            update_option('culturaltranslate_api_key', sanitize_text_field($_POST['culturaltranslate_api_key']));
            update_option('culturaltranslate_api_url', esc_url_raw($_POST['culturaltranslate_api_url']));
            update_option('culturaltranslate_default_source_lang', sanitize_text_field($_POST['culturaltranslate_default_source_lang']));
            update_option('culturaltranslate_default_target_lang', sanitize_text_field($_POST['culturaltranslate_default_target_lang']));
            update_option('culturaltranslate_auto_translate', isset($_POST['culturaltranslate_auto_translate']));
            update_option('culturaltranslate_brand_voice_id', intval($_POST['culturaltranslate_brand_voice_id']));
            update_option('culturaltranslate_apply_glossary', isset($_POST['culturaltranslate_apply_glossary']));
            
            echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'culturaltranslate') . '</p></div>';
        }
        
        include CULTURALTRANSLATE_PLUGIN_DIR . 'templates/admin/settings.php';
    }
    
    /**
     * AJAX: Test API connection
     */
    public static function ajax_test_connection() {
        check_ajax_referer('culturaltranslate_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }
        
        $api_key = sanitize_text_field($_POST['api_key']);
        $api_url = esc_url_raw($_POST['api_url']);
        
        $client = new CulturalTranslate_API_Client($api_key, $api_url);
        $result = $client->test_connection();
        
        if ($result['success']) {
            wp_send_json_success(array(
                'message' => __('Connection successful!', 'culturaltranslate'),
                'stats' => $result
            ));
        } else {
            wp_send_json_error(array(
                'message' => $result['error'] ?? __('Connection failed', 'culturaltranslate')
            ));
        }
    }
}
