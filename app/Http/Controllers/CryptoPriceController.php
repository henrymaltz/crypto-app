<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CryptoPrice;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Validator;

class CryptoPriceController extends Controller
{

    private $coinMap = [
        'BTCS' => 'bitcoin',
        'BCH' => 'bitcoin-cash',
        'LTC' => 'litecoin',
        'ETH' => 'ethereum',
        'DACXI' => 'dacxi',
        'LINK' => 'chainlink',
        'USDT' => 'tether',
        'XLM' => 'stellar',
        'DOT' => 'polkadot',
        'ADA' => 'cardano',
        'SOL' => 'solana',
        'AVAX' => 'avalanche-2',
        'LUNC' => 'terra-luna',
        'MATIC' => 'matic-network',
        'USDC' => 'usd-coin',
        'BNB' => 'binancecoin',
        'XRP' => 'ripple',
        'UNI' => 'uniswap',
        'MKR' => 'maker',
        'BAT' => 'basic-attention-token',
        'SAND' => 'the-sandbox',
        'EOS' => 'eos',
    ];

    private function getMappedCoinName($symbol)
    {
        return $this->coinMap[$symbol] ?? null;
    }

    private function fetchAndStorePrice($coin)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.coingecko.com']);
        $res = $client->request('GET', '/api/v3/simple/price?ids=' . $coin . '&vs_currencies=usd');

        $priceData = json_decode($res->getBody(), true);

        if (isset($priceData[$coin]['usd'])) {
            $price = $priceData[$coin]['usd'];
            CryptoPrice::create(['coin' => $coin, 'price' => $price]);
        }        
        return $priceData[$coin]['usd'];
    }

    public function getCurrentPrice(Request $request)
    {
        $validator = validator()->make(request()->all(),
        [
            'coin' => 'required|string|in:BTCS,BCH,LTC,ETH,DACXI,LINK,USDT,XLM,DOT,ADA,SOL,AVAX,LUNC,
                       MATIC,USDC,BNB,XRP,UNI,MKR,BAT,SAND,EOS'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $coin = $request->query('coin');
        $coinName = $this->getMappedCoinName($coin);
        if (!$coinName) {
            return response()->json([
                'status' => false,
                'message' => 'The coin was not found'
            ], 404);
        }
        $data = $this->fetchAndStorePrice($coinName);
        
        return response()->json([
            'status' => true,
            'message' => 'sucess',
            'data' => $data
        ], 200);

    }

    public function getPriceAt(Request $request)
    {
        $validator = validator()->make(request()->all(),
        [
            'coin' => 'required|string|in:BTCS,BCH,LTC,ETH,DACXI,LINK,USDT,XLM,DOT,ADA,SOL,AVAX,LUNC,
                       MATIC,USDC,BNB,XRP,UNI,MKR,BAT,SAND,EOS',
            'date' => 'required|date'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $coin = $request->query('coin');
        $coinName = $this->getMappedCoinName($coin);
        if (!$coinName) {
            return response()->json([
                'status' => false,
                'message' => 'The coin was not found'
            ], 404);
        }
        
        $date = $request->query('date');
        $timestamp = Carbon::parse($date);

        $data = CryptoPrice::where('coin', $coin)
            ->where('updated_at', '=', $timestamp)
            ->orderByDesc('updated_at')
            ->first();

        if (!$data) {
            $dateFormated = date('d-m-Y', strtotime($date));
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.coingecko.com']);
            $res = $client->request('GET', '/api/v3/coins/' . $coinName . '/history?date=' . $dateFormated . '&localization=false');
    
            $priceData = json_decode($res->getBody(), true);
            if (isset($priceData['market_data']['current_price']['usd'])) {
                $price = $priceData['market_data']['current_price']['usd'];
                return response()->json([
                    'status' => true,
                    'message' => 'sucess',
                    'data' => $price
                ], 200);    
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The data was not found',
                ], 422);    
            }
        } else {
            return response()->json([
                'status' => true,
                'message' => 'sucess',
                'data' => $data
            ], 200);
        }

    }
}