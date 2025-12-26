<?php

namespace App\Helpers;

class SEOHelper
{
    /**
     * Generate SEO meta tags for a page
     */
    public static function generateMetaTags($page)
    {
        $title = $page['title'] ?? config('app.name');
        $description = $page['description'] ?? 'Professional translation services powered by AI and human expertise.';
        $keywords = $page['keywords'] ?? 'translation, AI translation, professional translation, document translation';
        $image = $page['image'] ?? asset('images/og-image.jpg');
        $url = $page['url'] ?? url()->current();
        
        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'og:title' => $title,
            'og:description' => $description,
            'og:image' => $image,
            'og:url' => $url,
            'og:type' => 'website',
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $image,
            'canonical' => $url,
        ];
    }

    /**
     * Generate JSON-LD schema markup
     */
    public static function generateSchema($type, $data)
    {
        $schemas = [
            'Organization' => [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => config('app.name'),
                'url' => config('app.url'),
                'logo' => asset('images/logo.png'),
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+1-XXX-XXX-XXXX',
                    'contactType' => 'customer service',
                ],
                'sameAs' => [
                    'https://facebook.com/culturaltranslate',
                    'https://twitter.com/culturaltranslate',
                    'https://linkedin.com/company/culturaltranslate',
                ],
            ],
            'Service' => [
                '@context' => 'https://schema.org',
                '@type' => 'Service',
                'name' => $data['name'] ?? 'Translation Services',
                'description' => $data['description'] ?? 'Professional translation services',
                'provider' => [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                ],
                'serviceType' => $data['serviceType'] ?? 'Translation',
                'areaServed' => 'Worldwide',
            ],
            'Product' => [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $data['name'] ?? 'Translation Platform',
                'description' => $data['description'] ?? 'AI-powered translation platform',
                'brand' => [
                    '@type' => 'Brand',
                    'name' => config('app.name'),
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $data['price'] ?? '29.00',
                    'priceCurrency' => 'USD',
                ],
            ],
        ];

        return json_encode($schemas[$type] ?? [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate sitemap XML
     */
    public static function generateSitemap($pages)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($page['url']) . '</loc>';
            $xml .= '<lastmod>' . ($page['lastmod'] ?? date('Y-m-d')) . '</lastmod>';
            $xml .= '<changefreq>' . ($page['changefreq'] ?? 'weekly') . '</changefreq>';
            $xml .= '<priority>' . ($page['priority'] ?? '0.8') . '</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
}
