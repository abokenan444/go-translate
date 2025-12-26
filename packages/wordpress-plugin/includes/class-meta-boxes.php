<?php
/**
 * CulturalTranslate Meta Boxes
 */

if (!defined('ABSPATH')) {
    exit;
}

class CulturalTranslate_Meta_Boxes {
    
    /**
     * Initialize meta boxes
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post', array(__CLASS__, 'save_meta_boxes'));
        add_action('wp_ajax_culturaltranslate_translate_post', array(__CLASS__, 'ajax_translate_post'));
    }
    
    /**
     * Add meta boxes
     */
    public static function add_meta_boxes() {
        $post_types = array('post', 'page');
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'culturaltranslate_meta_box',
                __('CulturalTranslate', 'culturaltranslate'),
                array(__CLASS__, 'render_meta_box'),
                $post_type,
                'side',
                'high'
            );
        }
    }
    
    /**
     * Render meta box
     */
    public static function render_meta_box($post) {
        wp_nonce_field('culturaltranslate_meta_box', 'culturaltranslate_meta_box_nonce');
        
        $translated = get_post_meta($post->ID, '_culturaltranslate_translated', true);
        $source_lang = get_post_meta($post->ID, '_culturaltranslate_source_lang', true);
        $target_lang = get_post_meta($post->ID, '_culturaltranslate_target_lang', true);
        
        ?>
        <div class="culturaltranslate-meta-box">
            <p>
                <label>
                    <strong><?php _e('Source Language:', 'culturaltranslate'); ?></strong>
                </label>
                <select name="culturaltranslate_source_lang" style="width: 100%;">
                    <option value="en" <?php selected($source_lang, 'en'); ?>>English</option>
                    <option value="ar" <?php selected($source_lang, 'ar'); ?>>Arabic</option>
                    <option value="fr" <?php selected($source_lang, 'fr'); ?>>French</option>
                    <option value="es" <?php selected($source_lang, 'es'); ?>>Spanish</option>
                    <option value="de" <?php selected($source_lang, 'de'); ?>>German</option>
                </select>
            </p>
            
            <p>
                <label>
                    <strong><?php _e('Target Language:', 'culturaltranslate'); ?></strong>
                </label>
                <select name="culturaltranslate_target_lang" style="width: 100%;">
                    <option value="ar" <?php selected($target_lang, 'ar'); ?>>Arabic</option>
                    <option value="en" <?php selected($target_lang, 'en'); ?>>English</option>
                    <option value="fr" <?php selected($target_lang, 'fr'); ?>>French</option>
                    <option value="es" <?php selected($target_lang, 'es'); ?>>Spanish</option>
                    <option value="de" <?php selected($target_lang, 'de'); ?>>German</option>
                </select>
            </p>
            
            <p>
                <button type="button" class="button button-primary button-large" id="culturaltranslate-translate-btn" data-post-id="<?php echo esc_attr($post->ID); ?>">
                    <?php _e('Translate Content', 'culturaltranslate'); ?>
                </button>
            </p>
            
            <?php if ($translated): ?>
            <p class="culturaltranslate-status success">
                <span class="dashicons dashicons-yes"></span>
                <?php _e('Content has been translated', 'culturaltranslate'); ?>
            </p>
            <?php endif; ?>
            
            <div id="culturaltranslate-progress" style="display: none;">
                <p><?php _e('Translating...', 'culturaltranslate'); ?></p>
                <progress style="width: 100%;"></progress>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#culturaltranslate-translate-btn').on('click', function() {
                var postId = $(this).data('post-id');
                var sourceLang = $('select[name="culturaltranslate_source_lang"]').val();
                var targetLang = $('select[name="culturaltranslate_target_lang"]').val();
                
                $('#culturaltranslate-progress').show();
                $(this).prop('disabled', true);
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'culturaltranslate_translate_post',
                        nonce: '<?php echo wp_create_nonce('culturaltranslate_translate_post'); ?>',
                        post_id: postId,
                        source_lang: sourceLang,
                        target_lang: targetLang
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('<?php _e('Translation completed!', 'culturaltranslate'); ?>');
                            location.reload();
                        } else {
                            alert('<?php _e('Translation failed:', 'culturaltranslate'); ?> ' + response.data.message);
                        }
                    },
                    error: function() {
                        alert('<?php _e('Error occurred', 'culturaltranslate'); ?>');
                    },
                    complete: function() {
                        $('#culturaltranslate-progress').hide();
                        $('#culturaltranslate-translate-btn').prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    public static function save_meta_boxes($post_id) {
        if (!isset($_POST['culturaltranslate_meta_box_nonce'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['culturaltranslate_meta_box_nonce'], 'culturaltranslate_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        if (isset($_POST['culturaltranslate_source_lang'])) {
            update_post_meta($post_id, '_culturaltranslate_source_lang', sanitize_text_field($_POST['culturaltranslate_source_lang']));
        }
        
        if (isset($_POST['culturaltranslate_target_lang'])) {
            update_post_meta($post_id, '_culturaltranslate_target_lang', sanitize_text_field($_POST['culturaltranslate_target_lang']));
        }
    }
    
    /**
     * AJAX: Translate post
     */
    public static function ajax_translate_post() {
        check_ajax_referer('culturaltranslate_translate_post', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $source_lang = sanitize_text_field($_POST['source_lang']);
        $target_lang = sanitize_text_field($_POST['target_lang']);
        
        if (!current_user_can('edit_post', $post_id)) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }
        
        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(array('message' => 'Post not found'));
        }
        
        $client = new CulturalTranslate_API_Client();
        
        // Translate title
        $title_result = $client->translate($post->post_title, $source_lang, $target_lang);
        
        // Translate content
        $content_result = $client->translate($post->post_content, $source_lang, $target_lang);
        
        if ($title_result['success'] && $content_result['success']) {
            // Create translated version or update current
            wp_update_post(array(
                'ID' => $post_id,
                'post_title' => $title_result['translation'],
                'post_content' => $content_result['translation']
            ));
            
            update_post_meta($post_id, '_culturaltranslate_translated', true);
            update_post_meta($post_id, '_culturaltranslate_source_lang', $source_lang);
            update_post_meta($post_id, '_culturaltranslate_target_lang', $target_lang);
            
            wp_send_json_success(array(
                'message' => __('Translation completed', 'culturaltranslate'),
                'title' => $title_result['translation'],
                'content' => $content_result['translation']
            ));
        } else {
            wp_send_json_error(array(
                'message' => $title_result['error'] ?? $content_result['error'] ?? 'Translation failed'
            ));
        }
    }
}
