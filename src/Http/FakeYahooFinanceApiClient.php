<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FakeYahooFinanceApiClient implements FinanceApiClientInterface
{
    public static $statusCode = Response::HTTP_OK;
    public static $content = '';

    public function fetchStockProfile(string $symbol, string $region): JsonResponse
    {
        return new JsonResponse(self::$content, self::$statusCode, [], $isJson = true);
    }

    public static function setContent(array $overrides = []): void
    {
        self::$content = json_encode(
            array_merge([
                'symbol' => 'AMZN',
                'region' => 'US',
                'exchange_name' => 'NasdaqGS',
                'currency' => 'USD',
                'short_name' => 'Amazon.com, Inc'
            ], $overrides)
        );
    }
}
