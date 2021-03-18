# Testing Printable Output

1. Use the `assertStringContainsString()` method
2. Use `$commandTester->getDisplay()` to get output

e.g.

```php
$this->assertStringContainsString('Finance API Client Error', $commandTester->getDisplay());
```

## Random Tips

- to clear cache from any env
  - `APP_ENV=test symfony console cache:clear`
- asdf

## (1) `php bin/console` vs (2) `symfony console`

(read more into this, documentation and video don't seem to cover)

(2) detects that docker-compose is being used:

- points to DB service in `docker-compose.yaml`
  - to fix:
    1. prepend `DATABASE_URL` WITH `TEST_` (`DATABASE_URL` -> `TEST_DATABASE_URL`)
    2. `touch config/packages/test/doctrine.yaml`
    3. populate new file with contents
    4. clear test cache
