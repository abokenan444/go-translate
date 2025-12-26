<?php
/**
 * CulturalTranslate Shortcodes
 */

if (!defined('ABSPATH')) {
    exit;
}

class CulturalTranslate_Shortcodes {
    
    /**
     * Initialize shortcodes
     */
    public static function init() {
        add_shortcode('culturaltranslate', array(__CLASS__, 'translate_shortcode'));
        add_shortcode('ct_translate', array(__CLASS__, 'translate_shortcode'));
        add_shortcode('ct_verify', array(__CLASS__, 'verify_certificate_shortcode'));
    }
    
    /**
     * Translate shortcode
     * 
     * Usage: [culturaltranslate text="Hello World" source="en" target="ar"]
     */
    public static function translate_shortcode($atts) {
        $atts = shortcode_atts(array(
            'text' => '',
            'source' => get_option('culturaltranslate_default_source_lang', 'en'),
            'target' => get_option('culturaltranslate_default_target_lang', 'ar'),
            'brand_voice' => get_option('culturaltranslate_brand_voice_id', 0),
            'cache' => 'true',
        ), $atts);
        
        if (empty($atts['text'])) {
            return '<span class="culturaltranslate-error">' . __('No text provided', 'culturaltranslate') . '</span>';
        }
        
        // Check cache
        $cache_key = 'ct_' . md5($atts['text'] . $atts['source'] . $atts['target']);
        
        if ($atts['cache'] === 'true') {
            $cached = get_transient($cache_key);
            if ($cached !== false) {
                return '<span class="culturaltranslate-result cached" data-original="' . esc_attr($atts['text']) . '">' . esc_html($cached) . '</span>';
            }
        }
        
        // Make API call
        $client = new CulturalTranslate_API_Client();
        $result = $client->translate(
            $atts['text'],
            $atts['source'],
            $atts['target'],
            array(
                'brand_voice_id' => intval($atts['brand_voice']),
                'apply_glossary' => true
            )
        );
        
        if ($result['success'] && isset($result['translation'])) {
            $translation = $result['translation'];
            
            // Cache for 24 hours
            if ($atts['cache'] === 'true') {
                set_transient($cache_key, $translation, DAY_IN_SECONDS);
            }
            
            return '<span class="culturaltranslate-result" data-original="' . esc_attr($atts['text']) . '">' . esc_html($translation) . '</span>';
        } else {
            return '<span class="culturaltranslate-error">' . esc_html($result['error'] ?? __('Translation failed', 'culturaltranslate')) . '</span>';
        }
    }
    
    /**
     * Verify certificate shortcode
     * 
     * Usage: [ct_verify certificate_id="CT-2025-ABC123"]
     */
    public static function verify_certificate_shortcode($atts) {
        $atts = shortcode_atts(array(
            'certificate_id' => '',
        ), $atts);
        
        if (empty($atts['certificate_id'])) {
            return '<div class="culturaltranslate-verify-error">' . __('No certificate ID provided', 'culturaltranslate') . '</div>';
        }
        
        $client = new CulturalTranslate_API_Client();
        $result = $client->verify_certificate($atts['certificate_id']);
        
        if (!$result['success']) {
            return '<div class="culturaltranslate-verify-error">' . esc_html($result['error'] ?? __('Verification failed', 'culturaltranslate')) . '</div>';
        }
        
        ob_start();
        ?>
        <div class="culturaltranslate-certificate-verify">
            <div class="verify-status <?php echo esc_attr($result['status']); ?>">
                <span class="status-icon"></span>
                <span class="status-text">
                    <?php 
                    if ($result['valid']) {
                        echo __('Certificate Valid', 'culturaltranslate');
                    } else {
                        echo __('Certificate Invalid', 'culturaltranslate');
                    }
                    ?>
                </span>
            </div>
            
            <?php if ($result['valid']): ?>
            <div class="verify-details">
                <p><strong><?php _e('Certificate ID:', 'culturaltranslate'); ?></strong> <?php echo esc_html($result['certificate_id']); ?></p>
                <?php if (isset($result['issue_date'])): ?>
                <p><strong><?php _e('Issue Date:', 'culturaltranslate'); ?></strong> <?php echo esc_html($result['issue_date']); ?></p>
                <?php endif; ?>
                <?php if (isset($result['translator']['name'])): ?>
                <p><strong><?php _e('Translator:', 'culturaltranslate'); ?></strong> <?php echo esc_html($result['translator']['name']); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
