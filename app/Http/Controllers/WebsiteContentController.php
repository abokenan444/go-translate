<?php

namespace App\Http\Controllers;

use App\Models\WebsiteContent;
use Illuminate\Http\Request;

class WebsiteContentController extends Controller
{
    public function show($slug)
    {
        $content = WebsiteContent::where('page_slug', $slug)
            ->where('locale', app()->getLocale())
            ->where('status', 'published')
            ->firstOrFail();

        return view('website-content.show', compact('content'));
    }
}
