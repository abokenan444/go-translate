/**
 * CulturalTranslate WordPress Plugin - Admin JavaScript
 */

(function($) {
    'use strict';

    // Test API connection
    $('#ct-test-connection').on('click', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const $status = $('#ct-connection-status');
        const apiKey = $('#ct_api_key').val();
        const apiUrl = $('#ct_api_url').val();
        
        if (!apiKey || !apiUrl) {
            alert('Please enter API URL and API Key first.');
            return;
        }
        
        $button.prop('disabled', true).text('Testing...');
        $status.html('<span class="ct-spinner"></span> Testing connection...');
        
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ct_test_connection',
                api_key: apiKey,
                api_url: apiUrl,
                nonce: ct_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    $status.html('<span class="ct-status connected">✓ Connected</span>');
                    
                    // Show stats if available
                    if (response.data.stats) {
                        displayStats(response.data.stats);
                    }
                } else {
                    $status.html('<span class="ct-status disconnected">✗ Connection Failed</span>');
                    alert('Connection failed: ' + (response.data || 'Unknown error'));
                }
            },
            error: function() {
                $status.html('<span class="ct-status disconnected">✗ Connection Error</span>');
                alert('Failed to test connection. Please check your settings.');
            },
            complete: function() {
                $button.prop('disabled', false).text('Test Connection');
            }
        });
    });
    
    // Display API stats
    function displayStats(stats) {
        const html = `
            <div class="ct-stats">
                <div class="ct-stat-card">
                    <div class="number">${stats.translations || 0}</div>
                    <div class="label">Translations</div>
                </div>
                <div class="ct-stat-card">
                    <div class="number">${stats.characters || 0}</div>
                    <div class="label">Characters</div>
                </div>
                <div class="ct-stat-card">
                    <div class="number">${stats.cache_hit_rate || 0}%</div>
                    <div class="label">Cache Hit Rate</div>
                </div>
            </div>
        `;
        
        if ($('#ct-api-stats').length) {
            $('#ct-api-stats').html(html);
        } else {
            $('.ct-card').last().after('<div id="ct-api-stats" class="ct-card"><h2>API Statistics</h2>' + html + '</div>');
        }
    }
    
    // Translate post/page content
    $('.ct-translate-content').on('click', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const $preview = $('#ct-translation-preview');
        const targetLang = $('#ct-target-language').val();
        const tone = $('#ct-tone').val();
        const postId = $('#post_ID').val();
        
        if (!targetLang) {
            alert('Please select a target language.');
            return;
        }
        
        $button.prop('disabled', true).html('<span class="ct-spinner"></span> Translating...');
        $preview.html('<p><span class="ct-spinner"></span> Translating content...</p>').show();
        
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ct_translate_post',
                post_id: postId,
                target_language: targetLang,
                tone: tone,
                nonce: ct_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    const html = `
                        <h4>Translation Preview (${data.target_language.toUpperCase()})</h4>
                        <p><strong>Title:</strong></p>
                        <pre>${escapeHtml(data.translated_title)}</pre>
                        <p><strong>Content:</strong></p>
                        <pre>${escapeHtml(data.translated_content.substring(0, 500))}...</pre>
                        <p><small>Character count: ${data.character_count} | Processing time: ${data.processing_time_ms}ms</small></p>
                        <button type="button" class="ct-button-primary ct-apply-translation">Apply Translation</button>
                    `;
                    $preview.html(html);
                    
                    // Store translation data
                    $preview.data('translation', data);
                } else {
                    $preview.html('<p class="ct-notice error">Translation failed: ' + (response.data || 'Unknown error') + '</p>');
                }
            },
            error: function(xhr) {
                $preview.html('<p class="ct-notice error">Error: ' + (xhr.responseJSON?.message || 'Failed to translate content') + '</p>');
            },
            complete: function() {
                $button.prop('disabled', false).html('Translate Content');
            }
        });
    });
    
    // Apply translation to post
    $(document).on('click', '.ct-apply-translation', function(e) {
        e.preventDefault();
        
        const translation = $('#ct-translation-preview').data('translation');
        
        if (!translation) {
            alert('No translation data available.');
            return;
        }
        
        if (!confirm('This will replace the current title and content. Continue?')) {
            return;
        }
        
        // Update WordPress editor
        if (typeof tinymce !== 'undefined') {
            const editor = tinymce.get('content');
            if (editor) {
                editor.setContent(translation.translated_content);
            } else {
                $('#content').val(translation.translated_content);
            }
        } else {
            $('#content').val(translation.translated_content);
        }
        
        // Update title
        $('#title').val(translation.translated_title);
        
        // Show success message
        $('#ct-translation-preview').html('<p class="ct-notice success">✓ Translation applied! Don\'t forget to save/update the post.</p>');
    });
    
    // Real-time character counter
    let debounceTimer;
    $('#content, #title').on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updateCharacterCount, 500);
    });
    
    function updateCharacterCount() {
        const title = $('#title').val() || '';
        const content = $('#content').val() || '';
        const total = title.length + content.length;
        
        if ($('#ct-char-count').length === 0) {
            $('.ct-translation-panel').prepend('<p id="ct-char-count" style="margin:0;padding:10px;background:#f0f0f1;border-radius:4px;"></p>');
        }
        
        $('#ct-char-count').html(`<strong>Content Length:</strong> ${total.toLocaleString()} characters`);
    }
    
    // Auto-translate toggle
    $('#ct_auto_translate').on('change', function() {
        const $autoLangSection = $('#ct-auto-translate-languages');
        
        if ($(this).is(':checked')) {
            $autoLangSection.slideDown();
        } else {
            $autoLangSection.slideUp();
        }
    }).trigger('change');
    
    // Bulk translate posts
    $('#ct-bulk-translate').on('click', function(e) {
        e.preventDefault();
        
        const selectedLangs = [];
        $('input[name="ct_auto_translate_languages[]"]:checked').each(function() {
            selectedLangs.push($(this).val());
        });
        
        if (selectedLangs.length === 0) {
            alert('Please select at least one language for bulk translation.');
            return;
        }
        
        if (!confirm(`Translate all posts/pages to ${selectedLangs.length} language(s)? This may take a while.`)) {
            return;
        }
        
        // Start bulk translation process
        alert('Bulk translation feature coming soon! This will be processed in the background.');
    });
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    
    // Initialize on page load
    $(document).ready(function() {
        // Add visual feedback for form changes
        $('.ct-form-group input, .ct-form-group select').on('change', function() {
            $(this).css('border-color', '#667eea');
            setTimeout(() => {
                $(this).css('border-color', '');
            }, 1000);
        });
        
        // Show/hide advanced settings
        if ($('#ct-advanced-settings-toggle').length === 0) {
            $('.ct-card').last().after(`
                <button type="button" id="ct-advanced-settings-toggle" class="button">Show Advanced Settings</button>
                <div id="ct-advanced-settings" style="display:none;" class="ct-card">
                    <h2>Advanced Settings</h2>
                    <p>Advanced options for power users (Coming Soon)</p>
                </div>
            `);
        }
        
        $('#ct-advanced-settings-toggle').on('click', function() {
            $('#ct-advanced-settings').slideToggle();
            $(this).text(function(i, text) {
                return text === 'Show Advanced Settings' ? 'Hide Advanced Settings' : 'Show Advanced Settings';
            });
        });
    });
    
})(jQuery);
