# Updating Existing Records

## Checking for existance

```php
if ($stock = $this->entityManager->getRepository(Stock::class)->findOneBy(['symbol' => $symbol])) {
    ...
}
```

## Updating

```php
$stock = $this->serializer->deserialize($stockProfile->getContent(), Stock::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $stock]);
```

## Inserting

```php
$stock = $this->serializer->deserialize($stockProfile->getContent(), Stock::class, 'json');
```
