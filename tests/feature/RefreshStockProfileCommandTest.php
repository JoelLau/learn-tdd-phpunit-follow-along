<?php

namespace App\Tests\feature;

use App\Entity\Stock;
use App\Http\FakeYahooFinanceApiClient;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\Response;

class RefreshStockProfileCommmandTest extends DatabaseDependantTestCase
{
    /** @test */
    public function the_refresh_stock_profile_command_behaves_correctly_when_a_stock_records_does_not_exist()
    {
        // Arrange
        $application = new Application(self::$kernel);
        $command = $application->find('app:refresh-stock-profile');
        $commandTester = new CommandTester($command);

        FakeYahooFinanceApiClient::$content = '{"symbol":"AMZN","shortName":"Amazon.com, Inc.","region":"US","exchangeName":"NasdaqGS","currency":"USD","price":3135.73,"previousClose":3091.86,"priceChange":43.86999999999989}';

        // Act
        $commandTester->execute([
            'symbol' => 'AMZN',
            'region' => 'US'
        ]);
        $repo  = $this->entityManager->getRepository(Stock::class);
        $stock = $repo->findOneBy(['symbol' => 'AMZN']);

        // Assert
        $this->assertSame('USD', $stock->getCurrency());
        $this->assertSame('NasdaqGS', $stock->getExchangeName());
        $this->assertSame('AMZN', $stock->getSymbol());
        $this->assertSame('Amazon.com, Inc.', $stock->getShortName());
        $this->assertSame('US', $stock->getRegion());
        $this->assertGreaterThan(50, $stock->getPreviousClose());
        $this->assertGreaterThan(50, $stock->getPrice());
        $this->assertStringContainsString('Amazon.com, Inc. has been saved / updated.', $commandTester->getDisplay());
    }

    /** @test */
    public function non_200_status_code_responses_are_handled_correctly()
    {
        // Arrange
        $application = new Application(self::$kernel);
        $command = $application->find('app:refresh-stock-profile');
        $commandTester = new CommandTester($command);

        FakeYahooFinanceApiClient::$statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        FakeYahooFinanceApiClient::$content = 'Finance API Client Error';

        // Act
        $commandStatus = $commandTester->execute([
            'symbol' => 'AMZN',
            'region' => 'US'
        ]);
        $repo  = $this->entityManager->getRepository(Stock::class);
        $stockRecordCount = $repo->createQueryBuilder('stock')
            ->select('count(stock.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Assert
        $this->assertEquals(Command::FAILURE, $commandStatus);
        $this->assertEquals(0, $stockRecordCount);
        $this->assertStringContainsString('Finance API Client Error', $commandTester->getDisplay());
    }
}
