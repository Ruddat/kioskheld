<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Grundkonfiguration
    |--------------------------------------------------------------------------
    */

    'site_name' => 'Kioskheld',

    'default_locale' => config('localization.default', 'de'),

    'locales' => [
        'de' => 'de_DE',
        'en' => 'en_GB',
        'tr' => 'tr_TR',
    ],

    /*
    |--------------------------------------------------------------------------
    | Standardwerte
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'title' => 'Kioskheld – Dein Kiosk. Geliefert in Minuten.',

        'description' => 'Kioskheld verbindet dich mit teilnehmenden Kiosken und Getränkehändlern in deiner Nähe.',

        'robots' => 'index, follow',

        'og_type' => 'website',

        'twitter_card' => 'summary_large_image',
    ],

    /*
|--------------------------------------------------------------------------
| Indexierungsregeln
|--------------------------------------------------------------------------
|
| Die erste passende Regel gewinnt.
|
*/

    'robots' => [
        /*
     * Öffentlich, aber nicht als eigenständiges Suchergebnis geeignet.
     * Links dürfen verfolgt werden.
     */
        'noindex_follow' => [
            'postcode.check',
            'shops.selection',
            'shops.show',
        ],

        /*
     * Private, transaktionale oder personenbezogene Bereiche.
     */
        'noindex_nofollow' => [
            'checkout.*',

            'partner.thank-you',
            'partner.onboarding.*',

            'login',
            'logout',
            'register',

            'password.*',
            'verification.*',

            'dashboard',
            'profile.*',

            'admin.*',
            'vendor.*',
        ],
    ],

    /*
|--------------------------------------------------------------------------
| XML-Sitemap
|--------------------------------------------------------------------------
|
| Nur öffentliche, stabile und indexierbare GET-Seiten aufnehmen.
| Dynamische Shopseiten werden erst nach ausdrücklicher SEO-Freigabe ergänzt.
|
*/

    'sitemap' => [
        'routes' => [
            'home',
            'about',
            'faq',

            'partner.index',
            'partner.register',

            'legal.imprint',
            'legal.privacy',
            'legal.terms',
        ],
    ],

];
