<?php

namespace App\Services\Analytics;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsService
{
    /**
     * @return array<string, mixed>
     */
    public function build(int $days = 7): array
    {
        $days = max(1, min($days, 90));
        $since = now()->subDays($days);

return [
    'days' => $days,
    'overview' => $this->overview($since),
    'top_routes' => $this->topRoutes($since),
    'top_referers' => $this->topReferers($since),
    'status_codes' => $this->statusCodes($since),
    'slowest_routes' => $this->slowestRoutes($since),
    'traffic_timeline' => $this->trafficTimeline($days),
    'top_bots' => $this->topBots($since),
    'postcode_funnel' => $this->postcodeFunnel($since),
    'top_postcodes' => $this->topPostcodes($since),
];
    }

    /**
     * @return array<string, int|float>
     */
    private function overview(CarbonInterface $since): array
    {
        $stats = DB::table('visitor_logs')
            ->where('created_at', '>=', $since)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN is_bot = 1 THEN 1 ELSE 0 END) as bots,
                SUM(CASE WHEN is_bot = 0 THEN 1 ELSE 0 END) as humans,
                COUNT(DISTINCT CASE WHEN is_bot = 0 THEN visitor_hash END) as unique_visitors,
                AVG(response_time_ms) as avg_response_time
            ')
            ->first();

        $total = (int) ($stats->total ?? 0);
        $bots = (int) ($stats->bots ?? 0);

        return [
            'total' => $total,
            'humans' => (int) ($stats->humans ?? 0),
            'bots' => $bots,
            'unique_visitors' => (int) ($stats->unique_visitors ?? 0),
            'bot_rate' => $total > 0
                ? round(($bots / $total) * 100, 1)
                : 0,
            'avg_response_time' => (int) round(
                (float) ($stats->avg_response_time ?? 0)
            ),
        ];
    }

    /**
     * @return array<int, array{route: string, count: int}>
     */
    private function topRoutes(CarbonInterface $since): array
    {
        return DB::table('visitor_logs')
            ->where('created_at', '>=', $since)
            ->where('is_bot', false)
            ->whereNotNull('route_name')
            ->selectRaw('route_name, COUNT(*) as count')
            ->groupBy('route_name')
            ->orderByDesc('count')
            ->limit(15)
            ->get()
            ->map(fn ($row): array => [
                'route' => (string) $row->route_name,
                'count' => (int) $row->count,
            ])
            ->all();
    }

    /**
     * @return array<int, array{referer: string, count: int}>
     */
    private function topReferers(CarbonInterface $since): array
    {
        return DB::table('visitor_logs')
            ->where('created_at', '>=', $since)
            ->where('is_bot', false)
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->selectRaw('referer, COUNT(*) as count')
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn ($row): array => [
                'referer' => $this->shorten((string) $row->referer),
                'count' => (int) $row->count,
            ])
            ->all();
    }

    /**
     * @return array<int, int>
     */
    private function statusCodes(CarbonInterface $since): array
    {
        return DB::table('visitor_logs')
            ->where('created_at', '>=', $since)
            ->selectRaw('response_status, COUNT(*) as count')
            ->groupBy('response_status')
            ->orderBy('response_status')
            ->get()
            ->mapWithKeys(
                fn ($row): array => [
                    (int) $row->response_status => (int) $row->count,
                ]
            )
            ->all();
    }

    /**
     * @return array<int, array{route: string, avg_ms: int, count: int}>
     */
    private function slowestRoutes(CarbonInterface $since): array
    {
        return DB::table('visitor_logs')
            ->where('created_at', '>=', $since)
            ->whereNotNull('route_name')
            ->selectRaw('
                route_name,
                AVG(response_time_ms) as avg_ms,
                COUNT(*) as count
            ')
            ->groupBy('route_name')
            ->havingRaw('COUNT(*) >= 3')
            ->orderByDesc('avg_ms')
            ->limit(10)
            ->get()
            ->map(fn ($row): array => [
                'route' => (string) $row->route_name,
                'avg_ms' => (int) round((float) $row->avg_ms),
                'count' => (int) $row->count,
            ])
            ->all();
    }

    /**
     * @return array<int, array{date: string, humans: int, bots: int}>
     */
    private function trafficTimeline(int $days): array
    {
        $since = now()->subDays($days);

        $rows = DB::table('visitor_logs')
            ->where('created_at', '>=', $since)
            ->selectRaw('
                DATE(created_at) as date,
                is_bot,
                COUNT(*) as count
            ')
            ->groupByRaw('DATE(created_at), is_bot')
            ->orderBy('date')
            ->get();

        $timeline = [];

        for ($offset = $days; $offset >= 0; $offset--) {
            $date = now()->subDays($offset)->format('Y-m-d');

            $timeline[$date] = [
                'date' => $date,
                'humans' => 0,
                'bots' => 0,
            ];
        }

        foreach ($rows as $row) {
            $date = (string) $row->date;

            if (! isset($timeline[$date])) {
                continue;
            }

            if ((bool) $row->is_bot) {
                $timeline[$date]['bots'] = (int) $row->count;
            } else {
                $timeline[$date]['humans'] = (int) $row->count;
            }
        }

        return array_values($timeline);
    }

    /**
     * @return array<int, array{name: string, count: int}>
     */
    private function topBots(CarbonInterface $since): array
    {
        return DB::table('visitor_logs')
            ->where('created_at', '>=', $since)
            ->where('is_bot', true)
            ->whereNotNull('bot_name')
            ->selectRaw('bot_name, COUNT(*) as count')
            ->groupBy('bot_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn ($row): array => [
                'name' => (string) $row->bot_name,
                'count' => (int) $row->count,
            ])
            ->all();
    }

/**
 * @return array{
 *     searched: int,
 *     available: int,
 *     unavailable: int,
 *     failed: int,
 *     availability_rate: float
 * }
 */
private function postcodeFunnel(CarbonInterface $since): array
{
    $counts = DB::table('analytics_events')
        ->where('created_at', '>=', $since)
        ->whereIn('event_name', [
            'postcode_searched',
            'postcode_available',
            'postcode_unavailable',
            'postcode_check_failed',
        ])
        ->selectRaw('event_name, COUNT(*) as count')
        ->groupBy('event_name')
        ->pluck('count', 'event_name');

    $searched = (int) ($counts['postcode_searched'] ?? 0);
    $available = (int) ($counts['postcode_available'] ?? 0);
    $unavailable = (int) ($counts['postcode_unavailable'] ?? 0);
    $failed = (int) ($counts['postcode_check_failed'] ?? 0);

    $completedChecks = $available + $unavailable;

    return [
        'searched' => $searched,
        'available' => $available,
        'unavailable' => $unavailable,
        'failed' => $failed,
        'availability_rate' => $completedChecks > 0
            ? round(($available / $completedChecks) * 100, 1)
            : 0,
    ];
}

/**
 * @return array<int, array{
 *     postcode: string,
 *     searches: int,
 *     available: int,
 *     unavailable: int
 * }>
 */
private function topPostcodes(CarbonInterface $since): array
{
    return DB::table('analytics_events')
        ->where('created_at', '>=', $since)
        ->whereNotNull('postcode')
        ->whereIn('event_name', [
            'postcode_searched',
            'postcode_available',
            'postcode_unavailable',
        ])
        ->selectRaw('
            postcode,
            SUM(
                CASE
                    WHEN event_name = "postcode_searched"
                    THEN 1
                    ELSE 0
                END
            ) as searches,
            SUM(
                CASE
                    WHEN event_name = "postcode_available"
                    THEN 1
                    ELSE 0
                END
            ) as available,
            SUM(
                CASE
                    WHEN event_name = "postcode_unavailable"
                    THEN 1
                    ELSE 0
                END
            ) as unavailable
        ')
        ->groupBy('postcode')
        ->orderByDesc('searches')
        ->limit(20)
        ->get()
        ->map(fn ($row): array => [
            'postcode' => (string) $row->postcode,
            'searches' => (int) $row->searches,
            'available' => (int) $row->available,
            'unavailable' => (int) $row->unavailable,
        ])
        ->all();
}

    private function shorten(string $value, int $length = 80): string
    {
        return mb_strlen($value) <= $length
            ? $value
            : mb_substr($value, 0, $length - 1).'…';
    }
}
