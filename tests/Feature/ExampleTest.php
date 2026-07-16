<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_permanently_to_german_homepage(): void
    {
        $response = $this->get('/');

        $response
            ->assertStatus(301)
            ->assertRedirect('/de');
    }
}
