<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * Display all services overview
     */
    public function index()
    {
        return view('services.index');
    }

    /**
     * Display certified translation service page
     */
    public function certifiedTranslation()
    {
        return view('services.certified-translation');
    }

    /**
     * Display physical copy service page
     */
    public function physicalCopy()
    {
        return view('services.physical-copy');
    }

    /**
     * Display partners service page
     */
    public function partners()
    {
        return view('services.partners');
    }

    /**
     * Display affiliate program page
     */
    public function affiliate()
    {
        return view('services.affiliate');
    }

    /**
     * Display enterprise solutions page
     */
    public function enterprise()
    {
        return view('services.enterprise');
    }

    /**
     * Display document translation service page
     */
    public function documentTranslation()
    {
        return view('services.document-translation');
    }
}
