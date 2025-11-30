<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Main sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-main.xml') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $sitemap .= '</sitemap>';
        
        // Blog sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-blog.xml') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $sitemap .= '</sitemap>';
        
        $sitemap .= '</sitemapindex>';
        
        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }
    
    public function main()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Static pages
        $pages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/features', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => '/pricing', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/dashboard', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/realtime', 'priority' => '0.8', 'changefreq' => 'weekly'],
        ];
        
        foreach ($pages as $page) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url($page['url']) . '</loc>';
            $sitemap .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $sitemap .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $sitemap .= '<priority>' . $page['priority'] . '</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }
    
    public function blog()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Add blog posts if available
        try {
            $posts = DB::table('posts')->where('status', 'published')->get();
            
            foreach ($posts as $post) {
                $sitemap .= '<url>';
                $sitemap .= '<loc>' . url('/blog/' . $post->slug) . '</loc>';
                $sitemap .= '<lastmod>' . $post->updated_at . '</lastmod>';
                $sitemap .= '<changefreq>monthly</changefreq>';
                $sitemap .= '<priority>0.6</priority>';
                $sitemap .= '</url>';
            }
        } catch (\Exception $e) {
            // No blog posts table
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }
}
