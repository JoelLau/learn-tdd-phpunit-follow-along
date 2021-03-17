<?php

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Implements api described at: https://rapidapi.com/apidojo/api/yahoo-finance1?endpoint=apiendpoint_f787ce0f-17f7-40cf-a731-f141fd61cc08
 */
class YahooFinanceApiClient
{
    /** @var HttpClientInterface */
    private $httpClient;

    private const URL = 'https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-profile';
    private const X_RAPID_API_HOST = 'apidojo-yahoo-finance-v1.p.rapidapi.com';
    
    private $rapidApiKey;

    public function __construct(HttpClientInterface $httpClient, String $rapidApiKey)
    {
        $this->httpClient = $httpClient;
        $this->rapidApiKey = $rapidApiKey;
    }

    /**
     * @var $symbol
     * @var $symbol
     * @return array ['responseStatus' => string, 'content' => []]
     */
    public function fetchStockProfile($symbol, $region): array
    {
        $response = $this->httpClient->request(
            'GET',
            self::URL,
            [
            'query' => [
                'symbol' => $symbol,
                'region' => $region,
            ],
            'headers' => [
                'x-rapidapi-key' => $this->rapidApiKey,
                'x-rapidapi-host' => self::X_RAPID_API_HOST
            ]
            ]
        );
        dd($response);
        return [];
    }
}
