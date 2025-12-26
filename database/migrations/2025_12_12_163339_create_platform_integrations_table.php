<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create platform_integrations table for available integrations
        if (!Schema::hasTable('platform_integrations')) {
            Schema::create('platform_integrations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('category');
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->boolean('is_active')->default(true);
                $table->json('config')->nullable();
                $table->timestamps();
            });

            // Seed initial data
            DB::table('platform_integrations')->insert([
                // E-commerce
                ['name' => 'Shopify', 'slug' => 'shopify', 'category' => 'E-commerce', 'description' => 'Translate your entire store including products, collections, and checkout pages.', 'icon' => 'fab fa-shopify', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'WooCommerce', 'slug' => 'woocommerce', 'category' => 'E-commerce', 'description' => 'WordPress plugin for automatic translation of WooCommerce stores.', 'icon' => 'fab fa-wordpress', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Magento', 'slug' => 'magento', 'category' => 'E-commerce', 'description' => 'Enterprise-grade translation for Magento stores with multi-store support.', 'icon' => 'fab fa-magento', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Amazon Marketplace', 'slug' => 'amazon', 'category' => 'E-commerce', 'description' => 'Translate product listings for Amazon global marketplaces.', 'icon' => 'fab fa-amazon', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'eBay', 'slug' => 'ebay', 'category' => 'E-commerce', 'description' => 'Translate eBay listings for international buyers.', 'icon' => 'fas fa-shopping-cart', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                
                // Social Media
                ['name' => 'Facebook/Instagram', 'slug' => 'facebook', 'category' => 'Social Media', 'description' => 'Translate posts, ads, and stories for global audiences.', 'icon' => 'fab fa-facebook', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Twitter / X', 'slug' => 'twitter', 'category' => 'Social Media', 'description' => 'Auto-translate tweets and threads for global reach.', 'icon' => 'fab fa-twitter', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'LinkedIn', 'slug' => 'linkedin', 'category' => 'Social Media', 'description' => 'Translate professional content for international networks.', 'icon' => 'fab fa-linkedin', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'TikTok', 'slug' => 'tiktok', 'category' => 'Social Media', 'description' => 'Translate captions and descriptions for viral content.', 'icon' => 'fab fa-tiktok', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                
                // Messaging
                ['name' => 'Slack', 'slug' => 'slack', 'category' => 'Messaging', 'description' => 'Translate messages in real-time directly in your Slack channels.', 'icon' => 'fab fa-slack', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Microsoft Teams', 'slug' => 'teams', 'category' => 'Messaging', 'description' => 'Integrate translation capabilities into Teams chats and channels.', 'icon' => 'fab fa-microsoft', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'WhatsApp Business', 'slug' => 'whatsapp', 'category' => 'Messaging', 'description' => 'Auto-translate customer messages for global support.', 'icon' => 'fab fa-whatsapp', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Telegram', 'slug' => 'telegram', 'category' => 'Messaging', 'description' => 'Bot integration for instant message translation.', 'icon' => 'fab fa-telegram', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Discord', 'slug' => 'discord', 'category' => 'Messaging', 'description' => 'Bot for translating messages in Discord servers.', 'icon' => 'fab fa-discord', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Zoom', 'slug' => 'zoom', 'category' => 'Messaging', 'description' => 'Real-time transcription and translation for Zoom meetings.', 'icon' => 'fas fa-video', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                
                // CMS
                ['name' => 'WordPress', 'slug' => 'wordpress', 'category' => 'CMS', 'description' => 'Plugin for translating WordPress posts, pages, and custom post types.', 'icon' => 'fab fa-wordpress', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Contentful', 'slug' => 'contentful', 'category' => 'CMS', 'description' => 'Headless CMS integration for automatic content translation.', 'icon' => 'fas fa-cube', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Strapi', 'slug' => 'strapi', 'category' => 'CMS', 'description' => 'Open-source headless CMS with API-first translation.', 'icon' => 'fas fa-file-alt', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                
                // Development
                ['name' => 'GitHub', 'slug' => 'github', 'category' => 'Development', 'description' => 'Automate translation of documentation, README files, and issue comments.', 'icon' => 'fab fa-github', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'GitLab', 'slug' => 'gitlab', 'category' => 'Development', 'description' => 'Integrate translation into your GitLab CI/CD pipeline.', 'icon' => 'fab fa-gitlab', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'REST API', 'slug' => 'api', 'category' => 'Development', 'description' => 'Full-featured REST API for custom integrations.', 'icon' => 'fas fa-code', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('platform_integrations');
    }
};
