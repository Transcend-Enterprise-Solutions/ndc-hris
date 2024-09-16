<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BioTimeService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'Authorization' => 'JWT eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJ1c2VybmFtZSI6ImFkbWluMTIzIiwiZXhwIjoxNzI2NzMyMzYwLCJlbWFpbCI6ImFkbWluMTIzQGdtYWlsLmNvbSIsIm9yaWdfaWF0IjoxNzI2MTI3NTYwfQ.fdrrcnDE3aA7Q70ho79hCXXTZ3b22lX-Ub27R5gKyAo',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'allow_redirects' => true,
        ]);
    }

    public function getTransactions($params = [])
    {
        try {
            $apiUrl = "http://45.64.120.27:8082/iclock/api/transactions/";
            $response = $this->client->get($apiUrl, [
                'query' => $params
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new \Exception('Error fetching transactions: ' . $e->getMessage());
        }
    }
}