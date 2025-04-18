<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReviewEndpointsTest extends TestCase
{
    public function test_requires_at_least_one_filter()
    {
        $this->getJson('/api/v1/reviews')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['isbn','author','title']);
    }

    public function test_search_by_isbn_returns_expected_json()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response([
                'status'=>'OK',
                'num_results'=>1,
                'results'=>[[
                    'url'=>'u',
                    'publication_dt'=>'2025-01-01',
                    'byline'=>'B',
                    'book_title'=>'T',
                    'book_author'=>'A',
                    'summary'=>'S',
                    'isbn13'=>['9780307476463'],
                ]]
            ],200),
        ]);

        $this->getJson('/api/v1/reviews?isbn=9780307476463')
            ->assertOk()
            ->assertJsonPath('data.0.book_title','T')
            ->assertJsonCount(1,'data');
    }
}
