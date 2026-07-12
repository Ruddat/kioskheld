<?php

namespace App\Http\Controllers\Sitemap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SitemapIndexController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemaps.index')
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
