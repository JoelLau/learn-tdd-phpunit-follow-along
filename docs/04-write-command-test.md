# Writing Tests For Symfony Commands

1. create test class (RefreshStockProfileCommandTest)
2. copy setUp and tearDown functions: consider making a parent class to inherit from
3. use Application and CommandTester classes to run command:
   - `Symfony\Bundle\FrameworkBundle\Console\Application`
   - `Symfony\Component\Console\Tester\CommandTester`
4. run PHPUnit:
   - `symfony php bin/phpunit tests/feature/RefreshStockProfileCommandTest.php`
