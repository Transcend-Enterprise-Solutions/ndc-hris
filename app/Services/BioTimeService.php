<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BioTimeService
{
    protected $client;
    protected $authUrl = 'http://45.64.120.27:8082/jwt-api-token-auth/';
    protected $username = 'admin123'; 
    protected $password = 'admin123'; 
    protected $token;

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'allow_redirects' => true,
        ]);

        $this->authenticate(); // Automatically authenticate when the service is initialized
    }

    // Function to authenticate and get JWT token
    public function authenticate()
    {
        try {
            $response = $this->client->post($this->authUrl, [
                'json' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $this->token = $data['token']; // Store the JWT token

        } catch (RequestException $e) {
            throw new \Exception('Error authenticating: ' . $e->getMessage());
        }
    }

    // Function to fetch transactions with JWT token
    public function getTransactions($params = [])
    {
        try {
            $response = $this->client->get('http://45.64.120.27:8082/iclock/api/transactions/', [
                'headers' => [
                    'Authorization' => 'JWT ' . $this->token,
                ],
                'query' => $params
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            throw new \Exception('Error fetching transactions: ' . $e->getMessage());
        }
    }
}
