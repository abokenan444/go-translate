<?php
/**
 * CulturalTranslate Gutenberg Block
 */

if (!defined('ABSPATH')) {
    exit;
}

class CulturalTranslate_Gutenberg_Block {
    
    /**
     * Initialize Gutenberg block
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_block'));
        add_action('enqueue_block_editor_assets', array(__CLASS__, 'enqueue_block_editor_assets'));
    }
    
    /**
     * Register block
     */
    public static function register_block() {
        if (!function_exists('register_block_type')) {
            return;
        }
        
        register_block_type('culturaltranslate/translation-block', array(
            'editor_script' => 'culturaltranslate-block-editor',
            'editor_style' => 'culturaltranslate-block-editor',
            'style' => 'culturaltranslate-block',
            'render_callback' => array(__CLASS__, 'render_block'),
            'attributes' => array(
                'text' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'sourceLang' => array(
                    'type' => 'string',
                    'default' => 'en'
                ),
                'targetLang' => array(
                    'type' => 'string',
                    'default' => 'ar'
                ),
                'showOriginal' => array(
                    'type' => 'boolean',
                    'default' => false
                )
            )
        ));
    }
    
    /**
     * Enqueue block editor assets
     */
    public static function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'culturaltranslate-block-editor',
            CULTURALTRANSLATE_PLUGIN_URL . 'assets/js/block.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
            CULTURALTRANSLATE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'culturaltranslate-block-editor',
            CULTURALTRANSLATE_PLUGIN_URL . 'assets/css/block-editor.css',
            array('wp-edit-blocks'),
            CULTURALTRANSLATE_VERSION
        );
    }
    
    /**
     * Render block
     */
    public static function render_block($attributes) {
        if (empty($attributes['text'])) {
            return '';
        }
        
        $client = new CulturalTranslate_API_Client();
        $result = $client->translate(
            $attributes['text'],
            $attributes['sourceLang'],
            $attributes['targetLang']
        );
        
        if (!$result['success']) {
            return '<div class="culturaltranslate-error">' . esc_html($result['error']) . '</div>';
        }
        
        ob_start();
        ?>
        <div class="culturaltranslate-block">
            <?php if ($attributes['showOriginal']): ?>
            <div class="ct-original">
                <small><?php _e('Original:', 'culturaltranslate'); ?></small>
                <p><?php echo esc_html($attributes['text']); ?></p>
            </div>
            <?php endif; ?>
            
            <div class="ct-translation">
                <p><?php echo esc_html($result['translation']); ?></p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
