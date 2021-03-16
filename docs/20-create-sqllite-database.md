# Create SQLite Database

1. add configure the database url in the .env file: `DATABASE_URL="sqlite:///:memory:"`
2. set up doctrine test bundle to restart database every test: `composer require --dev dama/doctrine-test-bundle`
3. configure the extension in phpunit.xml.dist: `<extensions><extension class="DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension"/></extensions>`
