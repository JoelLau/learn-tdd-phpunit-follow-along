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

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        // Create dummy stock
        $stock = new Stock();
        $stock->setCurrency('USD');
        $stock->setExchangeName('NasdaqGS');
        $stock->setSymbol('AMZN');
        $stock->setShortName('Amazon.com, Inc.');
        $stock->setRegion('US');
        $stock->setPreviousClose(200);
        $stock->setPrice(200);
        $stock->setPriceChange(200);

        // Store in DB
        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
