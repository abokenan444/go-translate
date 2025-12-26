<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SandboxSiteTemplate;

class SandboxSiteTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'SaaS Landing',
                'slug' => 'saas-landing',
                'description' => 'SaaS landing page with hero, features, CTA',
                'is_active' => true,
                'config' => [
                    'pages' => ['/', '/pricing', '/about'],
                    'sections' => ['hero','features','cta','footer'],
                    'default_content' => [
                        'en' => [
                            'hero' => ['title' => 'Welcome to Cultural Translate','subtitle' => 'Localized AI for your market','cta' => 'Try Sandbox'],
                            'features' => ['items' => ['API v2','Realtime Translation','Webhook Simulator']],
                            'cta' => ['text' => 'Create your sandbox now'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'E-commerce',
                'slug' => 'ecommerce',
                'description' => 'Storefront with product grid and checkout',
                'is_active' => true,
                'config' => [
                    'pages' => ['/', '/products', '/cart'],
                    'sections' => ['hero','product-grid','testimonials','footer'],
                    'default_content' => [
                        'en' => [
                            'hero' => ['title' => 'Shop globally, speak locally','subtitle' => 'Translate product details instantly'],
                            'product-grid' => ['title' => 'Featured products'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'description' => 'Simple blog layout with posts and categories',
                'is_active' => true,
                'config' => [
                    'pages' => ['/', '/posts', '/categories'],
                    'sections' => ['hero','post-list','sidebar','footer'],
                    'default_content' => [
                        'en' => [
                            'hero' => ['title' => 'Global content, culturally aligned'],
                            'post-list' => ['title' => 'Latest posts'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($templates as $tpl) {
            SandboxSiteTemplate::updateOrCreate(['slug' => $tpl['slug']], $tpl);
        }
    }
}
