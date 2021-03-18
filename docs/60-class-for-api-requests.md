# Creating Cusomt Client Class For API Requests

## Registering a Symfony Service

```.yaml
    yahoo-finance-api-client:
        class: App\Http\YahooFinanceApiClient
        public: true
```

declare the following:

- name of the service (`yahoo-finance-api-client`)
- full class name (`App\Http\YahooFinanceApiClient`)
- make public (for tests) (`public: true`)

## Grouping tests

```php
/**
  * @test
  * @group integration
  */
  public function ...
```

Annotations:

- `@test` tells PHPUnit that the function is a test
- `@group` puts the function is a test group (for running / ignoring purposes)

## Rapid API Subscription

1. Register for an account
2. Subscribe to the chosen API

## Using Symfony HTTPClient

Link to [documentation](https://symfony.com/doc/current/http_client.html).

### 1. Use Dependency Injection to Include HttpClient

```php
/** @var HttpClientInterface */
private $httpClient;

public function __construct(HttpClientInterface $httpClient, String $rapidApiKey)
{
    $this->httpClient = $httpClient;
    $this->rapidApiKey = $rapidApiKey;
}
```

### 2. Make Request

```php
$response = $this->httpClient->request($verb, $url, $headers);
```

### 3. Access Status Code and Response Content

```php
$response->getStatusCode();
$response->getContent();
```
