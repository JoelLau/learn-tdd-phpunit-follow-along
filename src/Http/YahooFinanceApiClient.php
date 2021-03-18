<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Implements api described at: https://rapidapi.com/apidojo/api/yahoo-finance1?endpoint=apiendpoint_f787ce0f-17f7-40cf-a731-f141fd61cc08
 */
class YahooFinanceApiClient implements FinanceApiClientInterface
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
     * @return JsonResponse ['responseStatus' => string, 'content' => []]
     */
    public function fetchStockProfile($symbol, $region): JsonResponse
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
                    'x-rapidapi-host' => self::X_RAPID_API_HOST,
                    'x-rapidapi-key' => $this->rapidApiKey
                ]
            ]
        );

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return new JsonResponse('Finance API Client Error', Response::HTTP_BAD_REQUEST);
        }

        $stockProfile = json_decode($response->getContent())->price;
        $stockProfileAsArray = [
            'symbol' => $stockProfile->symbol,
            'shortName' => $stockProfile->shortName,
            'region' => $region,
            'exchangeName' => $stockProfile->exchangeName,
            'currency' => $stockProfile->currency,
            'price' => $stockProfile->regularMarketPrice->raw,
            'previousClose' => $stockProfile->regularMarketPreviousClose->raw,
            'priceChange' => $stockProfile->regularMarketPrice->raw - $stockProfile->regularMarketPreviousClose->raw
        ];

        return new JsonResponse($stockProfileAsArray, Response::HTTP_OK);
    }
}
