<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;

class LocalizedSeoTest extends TestCase
{
    public function test_german_homepage_contains_canonical_and_language_alternates(): void
    {
        $response = $this->get('/de');

        $response
            ->assertOk()
            ->assertSee('rel="canonical"', false)
            ->assertSee(
                'href="'.url('/de').'"',
                false
            )
            ->assertSee('hreflang="de"', false)
            ->assertSee(
                'href="'.url('/de').'"',
                false
            )
            ->assertSee('hreflang="en"', false)
            ->assertSee(
                'href="'.url('/en').'"',
                false
            )
            ->assertSee('hreflang="tr"', false)
            ->assertSee(
                'href="'.url('/tr').'"',
                false
            )
            ->assertSee('hreflang="x-default"', false);
    }

    public function test_localized_homepage_contains_open_graph_metadata(): void
    {
        $response = $this->get('/en');

        $response
            ->assertOk()
            ->assertSee(
                '<meta property="og:locale" content="en_GB">',
                false
            )
            ->assertSee(
                '<meta property="og:url" content="'.url('/en').'">',
                false
            )
            ->assertSee(
                'name="twitter:card"',
                false
            )
            ->assertSee(
                'content="summary_large_image"',
                false
            );
    }

    public function test_seo_urls_do_not_contain_internal_route_view_parameters(): void
    {
        $response = $this->get('/en');

        $response
            ->assertOk()
            ->assertDontSee('?view=', false)
            ->assertDontSee('&amp;status=', false)
            ->assertDontSee('&status=', false);
    }

    public function test_invalid_locale_returns_not_found(): void
    {
        $this->get('/fr')
            ->assertNotFound();
    }

    public function test_robots_file_blocks_private_and_transactional_areas(): void
    {
        $robots = file_get_contents(
            public_path('robots.txt')
        );

        $this->assertIsString($robots);

        $this->assertStringContainsString(
            'Disallow: /admin',
            $robots
        );

        $this->assertStringContainsString(
            'Disallow: /portal',
            $robots
        );

        $this->assertStringContainsString(
            'Disallow: /*/kasse',
            $robots
        );

        $this->assertStringContainsString(
            'Disallow: /*/partner/onboarding/',
            $robots
        );
    }
}
