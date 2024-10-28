<?php

namespace Tests\Feature;

use App\CryptoPrice;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CryptoPriceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetRecentPrice()
    {
        $expectedPrice = 67680;
        $crypto = CryptoPrice::create([
            'coin' => 'BTCS',
            'price' => $expectedPrice,
        ]);

        $response = $this->get('/api/price/recent?coin=BTCS');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            "usd" => $crypto->price,
        ]);
    }
}
