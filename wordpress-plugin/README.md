# CulturalTranslate WordPress Plugin

Seamlessly integrate CulturalTranslate's powerful AI-driven translation engine into your WordPress site.

## Features

- **One-Click Translation**: Translate posts, pages, and custom post types instantly
- **14 Languages Supported**: ar, de, en, es, fr, hi, it, ja, ko, nl, pt, ru, tr, zh
- **Multiple Tone Options**: Formal, Casual, Technical, Marketing
- **Auto-Translation**: Automatically translate new posts on publish
- **Inline Editing**: Preview and edit translations before publishing
- **Performance Optimized**: Uses Redis caching for lightning-fast translations
- **Cultural Context**: AI understands cultural nuances for accurate translations
- **Bulk Translation**: Translate multiple posts at once
- **SEO Friendly**: Maintains meta tags and permalinks structure

## Installation

### Method 1: Upload via WordPress Admin

1. Download the plugin ZIP file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" and select the ZIP file
4. Click "Install Now" and then "Activate"

### Method 2: Manual Installation

1. Extract the ZIP file
2. Upload the `culturaltranslate` folder to `/wp-content/plugins/`
3. Activate the plugin through the WordPress admin panel

### Method 3: WP-CLI

```bash
wp plugin install culturaltranslate.zip --activate
```

## Configuration

1. Go to **Settings → CulturalTranslate**
2. Enter your API credentials:
   - **API URL**: `https://culturaltranslate.com/api/sandbox/v1`
   - **API Key**: Your API key from CulturalTranslate dashboard
3. Click **Test Connection** to verify
4. Configure translation settings:
   - **Default Tone**: Choose preferred translation tone
   - **Auto-Translate**: Enable automatic translation on publish
   - **Target Languages**: Select languages for auto-translation

## Usage

### Translate Single Post/Page

1. Edit any post or page
2. Find the **CulturalTranslate** meta box in the sidebar
3. Select target language and tone
4. Click **Translate Content**
5. Preview the translation
6. Click **Apply Translation** to update the content
7. Save/Update the post

### Auto-Translation

1. Enable **Auto-Translate** in settings
2. Select target languages
3. When you publish a new post, it will automatically:
   - Translate to selected languages
   - Create translated versions (if WPML/Polylang installed)
   - Or save translations as custom fields

### Bulk Translation

1. Go to **Posts → All Posts**
2. Select multiple posts
3. Choose **Bulk Actions → Translate**
4. Select target languages
5. Click **Apply**

## API Endpoints Used

The plugin uses the following CulturalTranslate API endpoints:

- `POST /translate` - Translate content
- `GET /languages` - Get available languages
- `GET /usage` - Check API usage statistics
- `GET /cache/stats` - View cache performance

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- cURL extension enabled
- Valid CulturalTranslate API key

## Configuration Example

```php
// wp-config.php - Alternative configuration method
define('CULTURALTRANSLATE_API_URL', 'https://culturaltranslate.com/api/sandbox/v1');
define('CULTURALTRANSLATE_API_KEY', 'your-api-key-here');
```

## Hooks & Filters

### Actions

```php
// Before translation
do_action('culturaltranslate_before_translate', $post_id, $target_language);

// After translation
do_action('culturaltranslate_after_translate', $post_id, $target_language, $translated_content);
```

### Filters

```php
// Modify translation request
add_filter('culturaltranslate_translate_args', function($args, $post_id) {
    $args['tone'] = 'marketing';
    return $args;
}, 10, 2);

// Modify translated content before saving
add_filter('culturaltranslate_translated_content', function($content, $post_id, $language) {
    // Custom processing
    return $content;
}, 10, 3);
```

## Multisite Support

The plugin is fully compatible with WordPress Multisite:

- Network activate for all sites
- Or activate individually per site
- API keys can be configured per site
- Bulk operations work across network

## Compatibility

### Page Builders
- ✅ Gutenberg (Block Editor)
- ✅ Classic Editor
- ✅ Elementor
- ✅ WPBakery
- ✅ Divi Builder

### Translation Plugins
- ✅ WPML
- ✅ Polylang
- ✅ TranslatePress
- ✅ Weglot

### E-commerce
- ✅ WooCommerce
- ✅ Easy Digital Downloads

## Troubleshooting

### Connection Failed
- Verify API URL is correct
- Check API key is active
- Ensure server has cURL enabled
- Check firewall/SSL certificate

### Translation Not Working
- Check API usage limits
- Verify content length (max 5000 chars per request)
- Check WordPress debug logs

### Slow Performance
- Enable Redis caching on CulturalTranslate
- Use WordPress object caching
- Check server resources

## Support

- **Documentation**: https://culturaltranslate.com/docs/wordpress
- **Email**: support@culturaltranslate.com
- **GitHub**: https://github.com/culturaltranslate/wordpress-plugin

## Changelog

### Version 1.0.0 (December 2025)
- Initial release
- Support for 14 languages
- Auto-translation feature
- Bulk translation
- Redis cache integration
- Multiple tone support

## License

GPL v2 or later

## Credits

Developed by CulturalTranslate Team
