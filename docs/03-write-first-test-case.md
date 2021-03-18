# Write First Test Case

## Test Case

1. open test class: StockTest.php
2. write a new function
3. be sure to add `@test` annotation in a comment write before the new function
4. run the test: `$ symfony php bin/phpunit tests/StockTest.php`
5. according to the laws of TDD, the test should fail

## Creating an entity

1. run console make command: `symfony console make:entity Stock`
   - creates 2 files:
     - `src/Entiity/Stock.php`
     - `src/Repository/StockRepository.php`
   - pre-requisites for command to work:
     1. maker bundle must be installed: `composer require symfony/maker-bundle --dev` (should be installed by default)
     2. APP_ENV must be `dev`
2. follow through the instructions to add fields and functions
