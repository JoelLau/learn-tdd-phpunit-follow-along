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
}
