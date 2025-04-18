<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HistoryEndpointsTest extends TestCase
{
    public function test_requires_at_least_one_filter()
    {
        $this->getJson('/api/v1/history')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['author']);
    }

    public function test_invalid_offset_returns_422()
    {
        $this->getJson('/api/v1/history?author=Foo&offset=5')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['offset']);
    }

    public function test_search_by_author_returns_expected_json()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response([
                'status'=>'OK',
                'num_results'=>1,
                'offset'=>0,
                'results'=>[[
                    'title'=>'T','author'=>'A','description'=>'D','isbns'=>[['isbn13'=>'9780307476463']],
                ]]
            ],200),
        ]);

        $this->getJson('/api/v1/history?author=Foo')
            ->assertOk()
            ->assertJsonPath('data.status','OK')
            ->assertJsonCount(1,'data.results')        // <— use data.results not results.data
            ->assertJsonStructure([
                'data' => [
                    'status','num_results','offset',
                    'results' => [['title','author','description','isbns']]
                ]
            ]);
    }
}
