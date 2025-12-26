<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DocsController extends Controller
{
    public function index()
    {
        return view('docs.index');
    }

    public function show($page)
    {
        if (!View::exists("docs.$page")) {
            abort(404);
        }

        return view("docs.$page");
    }
}
