# Mocking API Responses

1. Create mock api class (`FakeYahooFinanceApiClient.php`)
2. Declare `public static` class attributes for mock responses
3. Define test services `touch config/services_test.yaml`
4. In the test suite, modify the class attributes to mock desired results

TIPS: Copy actual response for use in mock response

1. Use actual response in tests
   - you can do this by commenting entire contents of `services_test.yaml`
2. Use `dd($response)` to print response
3. Paste string output for use
4. Revert to using mock response
   - un-comment entire contents of `services_test.yaml`

## Using class attributes to mock API response

```php
FakeYahooFinanceApiClient::$content = '{"symbol":"AMZN","shortName":"Amazon.com, Inc.","region":"US","exchangeName":"NasdaqGS","currency":"USD","price":3135.73,"previousClose":3091.86,"priceChange":43.86999999999989}';
```

## Returning static attributes in mock response

```php
class FakeYahooFinanceApiClient implements FinanceApiClientInterface
{
    public static $statusCode = 200;
    public static $content = '';

    public function fetchStockProfile(string $symbol, string $region): array
    {
        return [
            'statusCode' => self::$statusCode,
            'content' => self::$content
        ];
    }
}
```
