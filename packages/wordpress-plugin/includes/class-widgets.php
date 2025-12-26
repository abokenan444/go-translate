<?php
/**
 * CulturalTranslate Widgets
 */

if (!defined('ABSPATH')) {
    exit;
}

class CulturalTranslate_Widgets {
    
    /**
     * Initialize widgets
     */
    public static function init() {
        add_action('widgets_init', array(__CLASS__, 'register_widgets'));
    }
    
    /**
     * Register widgets
     */
    public static function register_widgets() {
        register_widget('CulturalTranslate_Translation_Widget');
        register_widget('CulturalTranslate_Language_Switcher_Widget');
    }
}

/**
 * Translation Widget
 */
class CulturalTranslate_Translation_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'culturaltranslate_translation_widget',
            __('CulturalTranslate - Translation', 'culturaltranslate'),
            array('description' => __('Translate text in real-time', 'culturaltranslate'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        ?>
        <div class="culturaltranslate-widget">
            <form class="ct-translate-form">
                <textarea name="ct_text" placeholder="<?php _e('Enter text to translate...', 'culturaltranslate'); ?>" rows="4"></textarea>
                
                <div class="ct-language-select">
                    <select name="ct_source_lang">
                        <option value="en">English</option>
                        <option value="ar">Arabic</option>
                        <option value="fr">French</option>
                    </select>
                    <span class="ct-arrow">→</span>
                    <select name="ct_target_lang">
                        <option value="ar">Arabic</option>
                        <option value="en">English</option>
                        <option value="fr">French</option>
                    </select>
                </div>
                
                <button type="submit" class="button"><?php _e('Translate', 'culturaltranslate'); ?></button>
                
                <div class="ct-result" style="display: none;">
                    <h4><?php _e('Translation:', 'culturaltranslate'); ?></h4>
                    <div class="ct-result-text"></div>
                </div>
            </form>
        </div>
        <?php
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Translate', 'culturaltranslate');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'culturaltranslate'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Language Switcher Widget
 */
class CulturalTranslate_Language_Switcher_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'culturaltranslate_language_switcher',
            __('CulturalTranslate - Language Switcher', 'culturaltranslate'),
            array('description' => __('Switch between languages', 'culturaltranslate'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $languages = array(
            'en' => 'English',
            'ar' => 'العربية',
            'fr' => 'Français',
            'es' => 'Español',
            'de' => 'Deutsch'
        );
        
        ?>
        <div class="culturaltranslate-language-switcher">
            <ul class="ct-language-list">
                <?php foreach ($languages as $code => $name): ?>
                <li>
                    <a href="#" data-lang="<?php echo esc_attr($code); ?>">
                        <span class="flag-icon flag-icon-<?php echo esc_attr($code); ?>"></span>
                        <?php echo esc_html($name); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Languages', 'culturaltranslate');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'culturaltranslate'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}
