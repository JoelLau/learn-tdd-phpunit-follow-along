# Make Symfony Migration

> Database migrations are a way to safely update your database schema both locally and on production. Instead of running the doctrine:schema:update command or applying the database changes manually with SQL statements, migrations allow to replicate the changes in your database schema in a safe manner. - [Source](https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html)

1. Create migration file: `symfony console make:migration`
2. Perform migration : `symfony console doctrine:migrations:migrate`
