<?php
namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Use page content directly (no translations for now)
        $displayTitle = $page->title;
        $displayContent = $page->content;
        $displayMetaTitle = $page->meta_title ?? $page->title;
        $displayMetaDescription = $page->meta_description;
        
        return view('pages.dynamic', compact(
            'page',
            'displayTitle',
            'displayContent',
            'displayMetaTitle',
            'displayMetaDescription'
        ));
    }
}
