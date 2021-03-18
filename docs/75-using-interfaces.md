# Using PHP Interfaces

1. Create interface class (`FinanceApiClientInterface.php`)
2. Define methods in interface
3. Add `implements <interface_name>` to implementation class
4. If using dependency injection (see code block 75a.), also define which class should be used in services.yaml (code block 75b.)

## Code Block 75a

```php
public function __construct(
    EntityManagerInterface $entityManager,
    FinanceApiClientInterface $financeApiClient, // Used here!
    SerializerInterface $serializer
) {
    $this->entityManager = $entityManager;
    $this->financeApiClient = $financeApiClient;
    $this->serializer = $serializer;
    parent::__construct();
}
```

## Code Block 75b

```yaml
services:
  App\Http\FinanceApiClientInterface:
    class: App\Http\YahooFinanceApiClient
```
