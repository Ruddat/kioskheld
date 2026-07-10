<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerPageController extends Controller
{
    public function index(): View
    {
        return view('pages.partner');
    }

    public function register(): View
    {
        return view('pages.partner-register');
    }

    public function thankYou(): View
    {
        return view('pages.partner-thank-you');
    }
}
