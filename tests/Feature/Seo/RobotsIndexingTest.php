<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;

class RobotsIndexingTest extends TestCase
{
    public function test_public_marketing_page_is_indexable(): void
    {
        $response = $this->get('/de');

        $response
            ->assertOk()
            ->assertSee(
                'content="index, follow"',
                false
            )
            ->assertHeaderMissing('X-Robots-Tag');
    }

    public function test_shop_selection_is_noindex_follow(): void
    {
        $response = $this->get('/de/shops/auswahl');

        $response->assertHeader(
            'X-Robots-Tag',
            'noindex, follow'
        );
    }

    public function test_checkout_is_noindex_nofollow_even_when_it_redirects(): void
    {
        $response = $this->get('/de/kasse');

        $response->assertHeader(
            'X-Robots-Tag',
            'noindex, nofollow'
        );
    }

    public function test_partner_onboarding_is_noindex_nofollow(): void
    {
        $response = $this->get(
            '/de/partner/onboarding/not-a-valid-token'
        );

        $response->assertHeader(
            'X-Robots-Tag',
            'noindex, nofollow'
        );
    }

    public function test_login_is_noindex_nofollow(): void
    {
        $response = $this->get('/login');

        $response->assertHeader(
            'X-Robots-Tag',
            'noindex, nofollow'
        );
    }

    public function test_partner_landing_page_remains_indexable(): void
    {
        $response = $this->get('/de/partner');

        $response
            ->assertOk()
            ->assertSee(
                'content="index, follow"',
                false
            )
            ->assertHeaderMissing('X-Robots-Tag');
    }
}
