# Enforcing Coding Standards

## Tools Used And Their Purpose

### [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)

Set of 2 PHP Scripts:

1. `phpcs` (main) tokenizes PHP, JS and CSS files to detect violations
   - eslint for PHP
2. `phpcbf` automatically correct violations

#### Installation (via composer)

```bash
composer require --dev "squizlabs/php_codesniffer=*"
```

### [Symfony Coding Standard](https://github.com/djoos/Symfony-coding-standard)

Ruleset for Symfony projects, used by PHP Code sniffer
