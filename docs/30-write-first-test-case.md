# Write First Test Case

## Test Case

1. open test class: StockTest.php
2. write a new function
3. be sure to add `@test` annotation in a comment write before the new function
4. run the test: `$ symfony php bin/phpunit tests/StockTest.php`
5. according to the laws of TDD, the test should fail

## Creating an entity

(run `composer require symfony/maker-bundle --dev` if step 1 doesn't work)

1. run console make command: `symfony console make:entity Stock`
   - creates 2 files:
     - `src/Entiity/Stock.php`
     - `src/Repository/StockRepository.php`
2. follow through the instructions to add fields and functions
