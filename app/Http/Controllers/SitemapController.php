<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosting;

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
        
        // Careers sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-careers.xml') . '</loc>';
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
            ['url' => '/careers', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => '/integrations/github', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/integrations/wordpress', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/integrations/woocommerce', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/terms-of-service', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/privacy-policy', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/gdpr', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/security', 'priority' => '0.5', 'changefreq' => 'yearly'],
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
    
    public function careers()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Add career pages
        $jobs = JobPosting::open()->get();
        
        foreach ($jobs as $job) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . route('careers.show', $job->slug) . '</loc>';
            $sitemap .= '<lastmod>' . $job->updated_at->toAtomString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }
}
