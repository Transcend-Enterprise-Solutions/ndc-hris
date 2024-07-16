<?php

namespace App\Services;

use GuzzleHttp\Client;

class BioTimeService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('BIOTIME_BASE_URI'),
            'headers' => [
                'Authorization' => 'JWT ' . env('BIOTIME_API_KEY'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getTransactions($params = [])
    {
        $response = $this->client->get('/iclock/api/transactions/', ['query' => $params]);
        return json_decode($response->getBody(), true);
    }
}
