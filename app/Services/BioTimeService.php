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
                'Authorization' => 'JWT eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJ1c2VybmFtZSI6ImFkbWluIiwiZXhwIjoxNzIzNzA0Mjc1LCJlbWFpbCI6Impob25mcmFuY2lzZHVhc3J0ZTEyMzQ1QGdtYWlsLmNvbSIsIm9yaWdfaWF0IjoxNzIzMDk5NDc1fQ.VXoumkVKTqjY0VCnI2IVf8C4WyGZvO33Kp8I9KRAOho',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'allow_redirects' => true,
        ]);
    }

    public function getTransactions($params = [])
    {
        try {
            $apiUrl = "http://127.0.0.1:8082/iclock/api/transactions/";
            $response = $this->client->get($apiUrl, [
                'query' => $params
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new \Exception('Error fetching transactions: ' . $e->getMessage());
        }
    }
}