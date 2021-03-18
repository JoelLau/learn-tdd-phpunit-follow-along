# Create SQLite Database

## Use In-Memory Database

1. add configure the database url in the .env file: `DATABASE_URL="sqlite:///:memory:"`

## Reset DB On Test-Suite SetUp

1. set up doctrine test bundle to restart database every test: `composer require --dev dama/doctrine-test-bundle`
2. configure the extension in phpunit.xml.dist: `<extensions><extension class="DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension"/></extensions>`
3. use DatabasePrimer class from [this link](https://www.sitepoint.com/quick-tip-testing-symfony-apps-with-a-disposable-database/)
