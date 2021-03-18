<?php

namespace App\Command;

use App\Entity\Stock;
use App\Http\YahooFinanceApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RefreshStockProfileCommand extends Command
{
    protected static $defaultName = 'app:refresh-stock-profile';
    protected static $defaultDescription = 'Add a short description for your command';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var YahooFinanceApiClient */
    private $yahooFinanceApiClient;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        YahooFinanceApiClient $yahooFinanceApiClient,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->yahooFinanceApiClient = $yahooFinanceApiClient;
        $this->serializer = $serializer;
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
        // 1. Ping Yahoo API and grab the response (a stock profile) ['statusCode' => $statusCode, 'content' => $someJsonContent]
        $symbol = $input->getArgument('symbol');
        $region = $input->getArgument('region');
        $stockProfile = $this->yahooFinanceApiClient->fetchStockProfile($symbol, $region);

        // Handle non 200 status code responses
        if ($stockProfile['statusCode'] !== 200) {
            // TODO: handle error response
        }

        // 2. Use the stock profile  to create a records if it doesn't exist
        $stock = $this->serializer->deserialize($stockProfile['content'], Stock::class, 'json');

        // Store in DB
        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
