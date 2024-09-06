<?php

namespace App\Service;

use App\Entity\HeaderProcess;
use App\Entity\DetailProcess;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\DataType\SummaryData;

use DateTime;

class EtlProcessService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(
        SummaryData $summaryData, 
        string $fileName, 
        DateTime $executionDate
    ): void 
    {

        $data = [
            'recordsTotal' => $summaryData->getTotalRecords(),
            'genderTotals' => $summaryData->getTotalGenders(),
            'ageTotals' => $summaryData->getTotalAges(),
            'cityTotals' => $summaryData->getTotalCities(),
            'osTotal' => $summaryData->getTotalOs()
        ];

        $detailProcess = new DetailProcess();
        $detailProcess->setData($data);

        $headerProcess = new HeaderProcess();
        $headerProcess->setFileName($fileName);
        $headerProcess->setExecutionDate($executionDate);
        $headerProcess->addDetailProcess($detailProcess);

        $this->entityManager->persist($headerProcess);
        $this->entityManager->flush();

    }

}
