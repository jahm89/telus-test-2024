<?php

// src/Command/ProcessDataCommand.php
namespace App\Command;

use League\Csv\Writer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\DataType\TransformedData;

use DateTime;

#[AsCommand(
    name: 'app:etl-process-data-user',
    description: 'Fetch data from API, transform it, and save it as a CSV file.',
    hidden: false
)]
class EtlProcessCommand extends Command
{
    private $httpClient;
    const PATH_STORE_FOLDER = 'public/files/';
    private $transformedData;

    public function __construct(HttpClientInterface $httpClient, TransformedData $transformedData)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
        $this->transformedData = $transformedData;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Stating...');
        $today = new DateTime();
        $dateString = $today->format('Ymd');
        $rawDataFileName = self::PATH_STORE_FOLDER . "data_{$dateString}.json";
        $etlFileName = self::PATH_STORE_FOLDER . "ETL_{$dateString}.csv";
        $summaryFileName = self::PATH_STORE_FOLDER . "summary_{$dateString}.csv";

        // Fetch data from API
        $response = $this->httpClient->request('GET', 'https://dummyjson.com/users');
        $data = $response->toArray();
        file_put_contents($rawDataFileName, json_encode($data));

        $output->writeln('Raw data saved to ' . $rawDataFileName);

        // Transform JSON data to CSV
        $csv = Writer::createFromPath($etlFileName, 'w+');
        $csv->insertOne(['id', 'gender','age', 'city', 'os']);

        foreach ($data['users'] as $user) {
            $os = $this->getOs($user['userAgent']);
            $csv->insertOne([$user['id'], $user['gender'], $user['age'], $user['address']['city'], $os]);
            $this->transformedData->addData(1, $user['gender'], $user['address']['city'], $user['age'], $os);
        }

        $output->writeln("ETL saved to $etlFileName");

        // Create Summary
        $csvSummary = Writer::createFromPath($summaryFileName, 'w+');
        $csvSummary->insertOne(['registre', $this->transformedData->getTotalRecords()]);
        $csvSummary->insertOne(['gender', 'total']);

        foreach ($this->transformedData->getTotalGenders() as $key => $value) {
            $csvSummary->insertOne([$key, $value]);
        }

        $csvSummary->insertOne([]);
        $csvSummary->insertOne(['age', 'male', 'female', 'other']);
        foreach ($this->transformedData->getTotalAges() as $key => $value) {
            $row = [$key];
            foreach ($value as $_key => $_value) {
                $row[] = $_value;
            }
            $csvSummary->insertOne($row);
        }

        $csvSummary->insertOne([]);
        $csvSummary->insertOne(['city', 'male', 'female', 'other']);
        foreach ($this->transformedData->getTotalCities() as $key => $value) {
            $row = [$key];
            foreach ($value as $_key => $_value) {
                $row[] = $_value;
            }
            $csvSummary->insertOne($row);
        }
        
        $csvSummary->insertOne([]);
        $csvSummary->insertOne(['os', 'total']);
        foreach ($this->transformedData->getTotalOs() as $key => $value) {
            $csvSummary->insertOne([$key, $value]);
        }

        $output->writeln("Summary saved to $summaryFileName");
        
        return Command::SUCCESS;
    }

    private function getOs(string $userAgent): string {
        $os = "Unknown OS";

        $patterns = [
            '/Macintosh.*Mac OS X ([\d_\.]+)/' => 'Apple',
            '/Windows NT ([\d\.]+)/' => 'Windows',
            '/Linux/' => 'Linux',
        ];

        foreach ($patterns as $pattern => $osName) {
            if (preg_match($pattern, $userAgent, $matches)) {
                $os = $osName;
                break;
            }
        }

        return $os;
    }
}
