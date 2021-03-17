<?php

namespace App\Command;

use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshStockProfileCommand extends Command
{
    protected static $defaultName = 'app:refresh-stock-profile';
    protected static $defaultDescription = 'Add a short description for your command';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var YahooFinanceApiClient */
    private $yahooFinanceApiClient;

    public function __construct(EntityManagerInterface $entityManager, YahooFinanceApiClient $yahooFinanceApiClient)
    {
        $this->entityManager = $entityManager;
        $this->yahooFinanceApiClient = $yahooFinanceApiClient;
        parent::__construct();
    }
    /**
     * Specify arguments
     */
    protected function configure()
    {
        $this
            ->setDescription('Retrieve a stock profile from the Yahoo Finance API. Update the record in the DB')
            ->addArgument('symbol', InputArgument::REQUIRED, 'Stock symbol e.g. AMZN for Amazon')
            ->addArgument('region', InputArgument::REQUIRED, 'The region of the company e.g. US for United State')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * Perform function
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 1. Ping Yahoo API and grab the response (a profile)]]
        $symbol = $input->getArgument('symbol');
        $region = $input->getArgument('region');
        $stockProfile = $this->yahooFinanceApiClient->fetchStockProfile($symbol, $region);

        // 2. Use the stock profile  to create a records if it doesn't exist

        // Create dummy stock
        $stock = new Stock();
        $stock->setCurrency($stockProfile->currency);
        $stock->setExchangeName($stockProfile->exchangeName);
        $stock->setSymbol($stockProfile->symbol);
        $stock->setShortName($stockProfile->shortName);
        $stock->setRegion($stockProfile->region);
        $stock->setPreviousClose($stockProfile->price);
        $stock->setPrice($stockProfile->price);
        $priceChange = $stockProfile->price - $stockProfile->previousClose;
        $stock->setPriceChange($priceChange);

        // Store in DB
        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
