<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\AuditLog;

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
    }

    // Function to authenticate and get JWT token
    public function authenticate()
    {
        if ($this->token) {
            return;
        }

        try {
            $response = $this->client->post($this->authUrl, [
                'json' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            $this->token = $data['token'];
        } catch (RequestException $e) {
            $this->logError('auth_error', 'Error authenticating', [
                'message' => $e->getMessage(),
                'request' => $e->getRequest()->getBody()->getContents(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            // Instead of throwing an exception, we'll just set the token to null
            $this->token = null;
        }
    }

    public function getTransactions($params = [])
    {
        $this->authenticate(); // Try to authenticate before each request

        if (!$this->token) {
            // If we couldn't authenticate, log an error and return an empty array
            $this->logError('fetch_error', 'Unable to fetch transactions due to authentication failure', []);
            return [];
        }

        try {
            $response = $this->client->get('http://45.64.120.27:8082/iclock/api/transactions/', [
                'headers' => [
                    'Authorization' => 'JWT ' . $this->token,
                ],
                'query' => $params
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $this->logError('fetch_error', 'Error fetching transactions', [
                'message' => $e->getMessage(),
                'params' => $params,
                'request' => $e->getRequest()->getBody()->getContents(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            return [];
        }
    }

    private function logError($type, $message, $context)
    {
        AuditLog::create([
            'type' => $type,
            'message' => $message,
            'context' => $context,
        ]);
    }
}