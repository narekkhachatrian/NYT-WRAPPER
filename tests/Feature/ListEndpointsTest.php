<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ListEndpointsTest extends TestCase
{
    public function test_list_names_endpoint()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response([
                'status'=>'OK',
                'num_results'=>1,
                'results'=>[[
                    'list_name'=>'Hardcover Fiction',
                    'display_name'=>'Hardcover Fiction',
                    'list_name_encoded'=>'hardcover-fiction',
                    'oldest_published_date'=>'2020-01-05',
                    'newest_published_date'=>'2025-04-18',
                    'updated'=>'WEEKLY',
                ]]
            ], 200),
        ]);

        $this->getJson('/api/v1/lists/names')
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function test_list_snapshot_endpoint()
    {
        Http::fake([
            'api.nytimes.com/*' => Http::response([
                'status'=>'OK',
                'num_results'=>1,
                'results'=>[[
                    'list_name'=>'Hardcover Fiction',
                    'display_name'=>'Hardcover Fiction',
                    'bestsellers_date'=>'2025-04-12',
                    'published_date'=>'2025-04-18',
                    'rank'=>1,
                    'isbns'=>[['isbn13'=>'9780307476463']],
                    'book_details'=>[[
                        'title'=>'TEST',
                        'author'=>'Jane Doe',
                        'description'=>'',
                        'publisher'=>'Foo',
                        'primary_isbn13'=>'9780307476463'
                    ]]
                ]]
            ], 200),
        ]);

        $this->getJson('/api/v1/lists?list=hardcover-fiction')
            ->assertOk()
            ->assertJsonPath('data.results.list_name','hardcover-fiction');
    }
}
