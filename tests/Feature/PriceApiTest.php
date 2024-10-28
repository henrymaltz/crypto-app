<?php

namespace Tests\Feature;

use Tests\TestCase;

class PriceApiTest extends TestCase
{
    public function it_fetches_recent_price()
    {
        $response = $this->get('/api/price/recent?coin=ETH');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    }

    public function it_fetches_price_at_specific_date()
    {
        $response = $this->get('/api/price/at-date?coin=ETH&date=2024-10-28');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    }
}
