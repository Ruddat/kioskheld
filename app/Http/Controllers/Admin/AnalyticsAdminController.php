<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Analytics\AdminAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsAdminController extends Controller
{
    public function __invoke(
        Request $request,
        AdminAnalyticsService $analytics,
    ): View {
        $days = $request->integer('days', 7);

        if (! in_array($days, [1, 7, 30, 90], true)) {
            $days = 7;
        }

        return view('admin.analytics.index', [
            'analytics' => $analytics->build($days),
        ]);
    }
}
