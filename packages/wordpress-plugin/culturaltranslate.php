<?php
/**
 * Plugin Name: CulturalTranslate
 * Plugin URI: https://culturaltranslate.com/wordpress-plugin
 * Description: Intelligent cultural translation & localization directly in WordPress. Translate posts, pages, and custom content with AI-powered cultural context analysis.
 * Version: 1.0.0
 * Author: CulturalTranslate
 * Author URI: https://culturaltranslate.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: culturaltranslate
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin version
define('CULTURALTRANSLATE_VERSION', '1.0.0');
define('CULTURALTRANSLATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CULTURALTRANSLATE_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main CulturalTranslate Plugin Class
 */
class CulturalTranslate_Plugin {
    
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Include required files
     */
    private function includes() {
        require_once CULTURALTRANSLATE_PLUGIN_DIR . 'includes/class-api-client.php';
        require_once CULTURALTRANSLATE_PLUGIN_DIR . 'includes/class-settings.php';
        require_once CULTURALTRANSLATE_PLUGIN_DIR . 'includes/class-meta-boxes.php';
        require_once CULTURALTRANSLATE_PLUGIN_DIR . 'includes/class-shortcodes.php';
        require_once CULTURALTRANSLATE_PLUGIN_DIR . 'includes/class-widgets.php';
        require_once CULTURALTRANSLATE_PLUGIN_DIR . 'includes/class-gutenberg-block.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Initialize components
        CulturalTranslate_Settings::init();
        CulturalTranslate_Meta_Boxes::init();
        CulturalTranslate_Shortcodes::init();
        CulturalTranslate_Widgets::init();
        CulturalTranslate_Gutenberg_Block::init();
    }
    
    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain('culturaltranslate', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        wp_enqueue_style(
            'culturaltranslate-admin',
            CULTURALTRANSLATE_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            CULTURALTRANSLATE_VERSION
        );
        
        wp_enqueue_script(
            'culturaltranslate-admin',
            CULTURALTRANSLATE_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            CULTURALTRANSLATE_VERSION,
            true
        );
        
        wp_localize_script('culturaltranslate-admin', 'culturalTranslate', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('culturaltranslate_nonce'),
            'apiKey' => get_option('culturaltranslate_api_key', ''),
        ));
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_frontend_scripts() {
        wp_enqueue_style(
            'culturaltranslate-frontend',
            CULTURALTRANSLATE_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            CULTURALTRANSLATE_VERSION
        );
        
        wp_enqueue_script(
            'culturaltranslate-frontend',
            CULTURALTRANSLATE_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            CULTURALTRANSLATE_VERSION,
            true
        );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('CulturalTranslate', 'culturaltranslate'),
            __('CulturalTranslate', 'culturaltranslate'),
            'manage_options',
            'culturaltranslate',
            array($this, 'render_dashboard_page'),
            'dashicons-translation',
            30
        );
        
        add_submenu_page(
            'culturaltranslate',
            __('Dashboard', 'culturaltranslate'),
            __('Dashboard', 'culturaltranslate'),
            'manage_options',
            'culturaltranslate',
            array($this, 'render_dashboard_page')
        );
        
        add_submenu_page(
            'culturaltranslate',
            __('Translations', 'culturaltranslate'),
            __('Translations', 'culturaltranslate'),
            'manage_options',
            'culturaltranslate-translations',
            array($this, 'render_translations_page')
        );
        
        add_submenu_page(
            'culturaltranslate',
            __('Settings', 'culturaltranslate'),
            __('Settings', 'culturaltranslate'),
            'manage_options',
            'culturaltranslate-settings',
            array('CulturalTranslate_Settings', 'render_settings_page')
        );
    }
    
    /**
     * Render dashboard page
     */
    public function render_dashboard_page() {
        include CULTURALTRANSLATE_PLUGIN_DIR . 'templates/admin/dashboard.php';
    }
    
    /**
     * Render translations page
     */
    public function render_translations_page() {
        include CULTURALTRANSLATE_PLUGIN_DIR . 'templates/admin/translations.php';
    }
}

/**
 * Initialize plugin
 */
function culturaltranslate_init() {
    return CulturalTranslate_Plugin::get_instance();
}

// Start the plugin
culturaltranslate_init();

/**
 * Activation hook
 */
register_activation_hook(__FILE__, function() {
    // Set default options
    add_option('culturaltranslate_version', CULTURALTRANSLATE_VERSION);
    add_option('culturaltranslate_api_key', '');
    add_option('culturaltranslate_api_url', 'https://api.culturaltranslate.com');
    add_option('culturaltranslate_default_source_lang', 'en');
    add_option('culturaltranslate_default_target_lang', 'ar');
    add_option('culturaltranslate_auto_translate', false);
    
    // Flush rewrite rules
    flush_rewrite_rules();
});

/**
 * Deactivation hook
 */
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});
