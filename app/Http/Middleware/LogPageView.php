<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LogPageView
{
    /**
     * Bekannte Bots, Crawler und Scanner.
     *
     * @var array<string, string>
     */
    private const BOT_PATTERNS = [
        'googlebot' => 'Google',
        'bingbot' => 'Bing',
        'duckduckbot' => 'DuckDuckGo',
        'yandexbot' => 'Yandex',
        'baiduspider' => 'Baidu',
        'applebot' => 'Apple',
        'facebookexternalhit' => 'Facebook',
        'twitterbot' => 'Twitter',
        'linkedinbot' => 'LinkedIn',
        'pinterest' => 'Pinterest',
        'whatsapp' => 'WhatsApp',
        'telegrambot' => 'Telegram',
        'discordbot' => 'Discord',

        'ahrefsbot' => 'Ahrefs',
        'semrushbot' => 'Semrush',
        'mj12bot' => 'Majestic',
        'dotbot' => 'DotBot',

        'nikto' => 'Nikto',
        'sqlmap' => 'SQLMap',
        'nmap' => 'Nmap',
        'masscan' => 'Masscan',
        'censys' => 'Censys',

        'curl' => 'cURL',
        'wget' => 'Wget',
        'python-requests' => 'Python Requests',
        'python-urllib' => 'Python urllib',
        'go-http-client' => 'Go HTTP',
        'node-fetch' => 'Node Fetch',
        'axios' => 'Axios',
        'scrapy' => 'Scrapy',
        'selenium' => 'Selenium',
        'puppeteer' => 'Puppeteer',
        'headless' => 'Headless Browser',

        'crawler' => 'Generic Crawler',
        'spider' => 'Generic Spider',
        'scraper' => 'Generic Scraper',
        'bot' => 'Generic Bot',
    ];

    /**
     * Dateiendungen, die nicht als Seitenaufruf zählen.
     *
     * @var list<string>
     */
    private const SKIP_EXTENSIONS = [
        '.css',
        '.js',
        '.mjs',
        '.map',
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
        '.svg',
        '.ico',
        '.webp',
        '.avif',
        '.woff',
        '.woff2',
        '.ttf',
        '.eot',
        '.mp3',
        '.mp4',
        '.webm',
        '.pdf',
        '.xml',
        '.txt',
        '.webmanifest',
    ];

    /**
     * Pfade, die nicht protokolliert werden.
     *
     * @var list<string>
     */
    private const SKIP_PREFIXES = [
        '/api/',
        '/livewire/',
        '/build/',
        '/storage/',
        '/vendor/',
        '/sanctum/',
        '/broadcasting/',
        '/_ignition/',
        '/telescope/',
        '/horizon/',
        '/.well-known/',
        '/up',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = microtime(true);

        /** @var Response $response */
        $response = $next($request);

        try {
            $this->logRequest(
                request: $request,
                response: $response,
                startedAt: $startedAt,
            );
        } catch (\Throwable $exception) {
            /*
             * Analytics darf niemals einen Seitenaufruf zerstören.
             * Später können wir hier bei Bedarf einen eigenen Log-Kanal verwenden.
             */
        }

        return $response;
    }

    private function logRequest(
        Request $request,
        Response $response,
        float $startedAt,
    ): void {
        if ($this->shouldSkip($request)) {
            return;
        }

        $userAgent = (string) $request->userAgent();
        [$isBot, $botName] = $this->detectBot($userAgent);

        $routeName = $request->route()?->getName();

        if (is_string($routeName)) {
            $routeName = mb_substr($routeName, 0, 200);
        }

        $ipAddress = (string) ($request->ip() ?? 'unknown');

        $visitorHash = hash_hmac(
            'sha256',
            $ipAddress,
            (string) config('app.key'),
        );

        $referer = $request->header('referer');

        DB::table('visitor_logs')->insert([
            'visitor_hash' => $visitorHash,
            'session_id' => $request->hasSession()
                ? mb_substr($request->session()->getId(), 0, 120)
                : null,
            'user_agent' => $userAgent !== ''
                ? mb_substr($userAgent, 0, 500)
                : null,
            'method' => $request->method(),
            'route_name' => $routeName,
            'url' => mb_substr($request->fullUrl(), 0, 2000),
            'referer' => is_string($referer)
                ? mb_substr($referer, 0, 2000)
                : null,
            'response_status' => $response->getStatusCode(),
            'response_time_ms' => (int) round(
                (microtime(true) - $startedAt) * 1000
            ),
            'is_bot' => $isBot,
            'bot_name' => $botName,
            'locale' => app()->getLocale(),
            'shop_id' => $this->resolveShopId($request),
            'customer_id' => $this->resolveCustomerId(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function shouldSkip(Request $request): bool
    {
        if ($request->method() !== 'GET') {
            return true;
        }

        /*
     * Interne und administrative Routen sollen die öffentlichen
     * Besucherzahlen nicht verfälschen.
     */
        $routeName = $request->route()?->getName();

        if (is_string($routeName)) {
            $excludedRoutePrefixes = [
                'admin.',
                'profile.',
                'password.',
                'verification.',
            ];

            foreach ($excludedRoutePrefixes as $prefix) {
                if (str_starts_with($routeName, $prefix)) {
                    return true;
                }
            }

            if (in_array($routeName, [
                'dashboard',
                'login',
                'logout',
                'register',
            ], true)) {
                return true;
            }
        }

        $path = '/' . ltrim($request->path(), '/');

        foreach (self::SKIP_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        $lowerPath = mb_strtolower($path);

        foreach (self::SKIP_EXTENSIONS as $extension) {
            if (str_ends_with($lowerPath, $extension)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{0: bool, 1: string|null}
     */
    private function detectBot(string $userAgent): array
    {
        if ($userAgent === '') {
            return [true, 'Empty User-Agent'];
        }

        $normalizedUserAgent = mb_strtolower($userAgent);

        foreach (self::BOT_PATTERNS as $pattern => $botName) {
            if (str_contains($normalizedUserAgent, $pattern)) {
                return [true, $botName];
            }
        }

        return [false, null];
    }

    private function resolveShopId(Request $request): ?int
    {
        $routeShop = $request->route('shop');

        if (is_object($routeShop) && isset($routeShop->id)) {
            return (int) $routeShop->id;
        }

        $sessionShopId = $request->session()->get('kioskheld.cart.shop.id');

        if (is_numeric($sessionShopId)) {
            return (int) $sessionShopId;
        }

        return null;
    }

    private function resolveCustomerId(): ?int
    {
        $userId = auth()->id();

        return is_numeric($userId) ? (int) $userId : null;
    }
}
