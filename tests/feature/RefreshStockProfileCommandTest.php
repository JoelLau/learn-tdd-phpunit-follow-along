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

        FakeYahooFinanceApiClient::$content = '{"symbol":"AMZN","shortName":"Amazon.com, Inc.","region":"US","exchangeName":"NasdaqGS","currency":"USD","price":3135.73,"previousClose":3091.86,"priceChange":43.87}';

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
    public function the_refresh_stock_profile_command_updates_existing_records_correctly()
    {
        // Arrange
        $stock = new Stock();
        $stock->setSymbol('AMZN');
        $stock->setRegion('US');
        $stock->setExchangeName('NasdaqGS');
        $stock->setCurrency('USD');
        $stock->setPreviousClose(3000);
        $stock->setPrice(3100);
        $stock->setPriceChange(100);
        $stock->setShortName('Amazon.com, Inc.');

        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        $application = new Application(self::$kernel);
        $command = $application->find('app:refresh-stock-profile');
        $commandTester = new CommandTester($command);

        FakeYahooFinanceApiClient::$statusCode = Response::HTTP_OK;
        FakeYahooFinanceApiClient::setContent([
            "price" => 3135.73,
            "previous_close" => 3091.86,
            "price_change" => 43.87
        ]);

        // Act
        $commandStatus = $commandTester->execute([
            'symbol' => 'AMZN',
            'region' => 'US'
        ]);
        $repo  = $this->entityManager->getRepository(Stock::class);
        $stockId = $stock->getId();
        $stockRecord = $repo->find($stockId);
        $stockRecordCount = $repo->createQueryBuilder('stock')
            ->select('count(stock.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Assert
        $this->assertEquals(Command::SUCCESS, $commandStatus);
        $this->assertEquals(3135.73, $stockRecord->getPrice());
        $this->assertEquals(3091.86, $stockRecord->getPreviousClose());
        $this->assertEquals(43.87, $stockRecord->getPriceChange());
        $this->assertEquals(1, $stockRecordCount);
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
