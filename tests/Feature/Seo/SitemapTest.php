<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_sitemap_index_contains_all_supported_locales(): void
    {
        $response = $this->get('/sitemap.xml');

        $response
            ->assertOk()
            ->assertHeader(
                'Content-Type',
                'application/xml; charset=UTF-8'
            )
            ->assertSee(
                route(
                    'seo.sitemap.locale',
                    ['locale' => 'de']
                ),
                false
            )
            ->assertSee(
                route(
                    'seo.sitemap.locale',
                    ['locale' => 'en']
                ),
                false
            )
            ->assertSee(
                route(
                    'seo.sitemap.locale',
                    ['locale' => 'tr']
                ),
                false
            );
    }

    public function test_german_sitemap_contains_public_marketing_pages(): void
    {
        $response = $this->get('/sitemaps/de.xml');

        $response
            ->assertOk()
            ->assertSee(
                route('home', ['locale' => 'de']),
                false
            )
            ->assertSee(
                route('about', ['locale' => 'de']),
                false
            )
            ->assertSee(
                route('faq', ['locale' => 'de']),
                false
            )
            ->assertSee(
                route('partner.index', ['locale' => 'de']),
                false
            )
            ->assertSee(
                route('legal.imprint', ['locale' => 'de']),
                false
            );
    }

    public function test_locale_sitemap_contains_hreflang_alternates(): void
    {
        $response = $this->get('/sitemaps/en.xml');

        $response
            ->assertOk()
            ->assertSee('hreflang="de"', false)
            ->assertSee('hreflang="en"', false)
            ->assertSee('hreflang="tr"', false)
            ->assertSee('hreflang="x-default"', false)
            ->assertSee(
                route('home', ['locale' => 'de']),
                false
            )
            ->assertSee(
                route('home', ['locale' => 'en']),
                false
            )
            ->assertSee(
                route('home', ['locale' => 'tr']),
                false
            );
    }

    public function test_sitemap_does_not_contain_private_or_transactional_pages(): void
    {
        $response = $this->get('/sitemaps/de.xml');

        $response
            ->assertOk()
            ->assertDontSee('/kasse', false)
            ->assertDontSee('/shops/auswahl', false)
            ->assertDontSee('/bestellung/danke', false)
            ->assertDontSee('/partner/onboarding/', false)
            ->assertDontSee('/admin', false)
            ->assertDontSee('/portal', false);
    }

    public function test_unsupported_locale_sitemap_returns_not_found(): void
    {
        $this->get('/sitemaps/fr.xml')
            ->assertNotFound();
    }

    public function test_sitemap_does_not_contain_internal_route_parameters(): void
    {
        $response = $this->get('/sitemaps/de.xml');

        $response
            ->assertOk()
            ->assertDontSee('?view=', false)
            ->assertDontSee('&amp;status=', false)
            ->assertDontSee('&status=', false);
    }
}
