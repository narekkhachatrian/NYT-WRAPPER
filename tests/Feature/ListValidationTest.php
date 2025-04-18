<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ListValidationTest extends TestCase
{
    public function test_missing_list_param_returns_422()
    {
        $this->getJson('/api/v1/lists')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['list']);
    }

    public function test_invalid_list_slug_returns_422()
    {
        $this->getJson('/api/v1/lists?list=Bad*Slug')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['list']);
    }

    public function test_invalid_date_format_returns_422()
    {
        $this->getJson('/api/v1/lists?list=hardcover-fiction&published_date=2025-13-01')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['published_date']);
    }

    public function test_bad_offset_returns_422()
    {
        $this->getJson('/api/v1/lists?list=hardcover-fiction&offset=5')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['offset']);
    }
}
