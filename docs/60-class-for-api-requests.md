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
