<?php
namespace App\DataType;

class TransformedData
{
    private $totalRecords;
    private $totalGenders;
    private $totalAges;
    private $totalCities;
    private $totalOs;

    const GENDERS = [
        'male' => 0,
        'female' => 0,
        'other' => 0
    ];

    const AGE_GROUPS = [
        '00-10' => [0, 10],
        '11-20' => [11, 20],
        '21-30' => [21, 30],
        '31-40' => [31, 40],
        '41-50' => [41, 50],
        '51-60' => [51, 60],
        '61-70' => [61, 70],
        '71-80' => [71, 80],
        '81-90' => [81, 90],
        '91'    => [91, PHP_INT_MAX],
    ];

    public function __construct()
    {
        $this->totalRecords = 0;
        $this->totalGenders = self::GENDERS;
        $this->totalAges = [
            '00-10' => self::GENDERS,
            '11-20' => self::GENDERS,
            '21-30' => self::GENDERS,
            '31-40' => self::GENDERS,
            '41-50' => self::GENDERS,
            '51-60' => self::GENDERS,
            '61-70' => self::GENDERS,
            '71-80' => self::GENDERS,
            '81-90' => self::GENDERS,
            '91' => self::GENDERS,
        ];
        $this->totalCities = [];
        $this->totalOs = [
            'Windows' => 0,
            'Apple' => 0,
            'Linux' => 0,
            'Other' => 0
        ];
    }

    // Getters
    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    public function getTotalGenders(): array
    {
        return $this->totalGenders;
    }

    public function getTotalAges(): array
    {
        return $this->totalAges;
    }

    public function getTotalCities(): array
    {
        return $this->totalCities;
    }

    public function getTotalOs(): array
    {
        return $this->totalOs;
    }

    // Setters
    public function setTotalRecords(int $totalRecords): void
    {
        $this->totalRecords = $totalRecords;
    }

    public function incrementGenderGroup(string $gender): void
    {
        if (array_key_exists($gender, $this->totalGenders)) {
            $this->totalGenders[$gender]++;
        } else {
            $this->totalGenders['other']++;
        }
    }

    public function incrementAgeGroup(string $age, string $gender): void
    {
        $ageGroup = $this->getAgeGroup(intval($age));
        if (array_key_exists($ageGroup, $this->totalAges)) {
            $this->totalAges[$ageGroup][$gender]++;
        }
    }

    public function incrementCityGroup(string $city, string $gender): void
    {
        if (array_key_exists($city, $this->totalCities)) {
            $this->totalCities[$city][$gender]++;
        } else {
            $this->totalCities[$city] = [
                'male' => 0,
                'female' => 0,
                'other' => 0
            ];
            $this->totalCities[$city][$gender] = 1;
        }
    }

    public function incrementOsGroup(string $os): void
    {
        if (array_key_exists($os, $this->totalOs)) {
            $this->totalOs[$os]++;
        } else {
            $this->totalOs['Other']++;
        }
    }

    // Example method to add data
    public function addData(int $records, string $gender, string $city, string $age, string $os): void
    {
        $this->totalRecords += $records;
        $this->incrementGenderGroup($gender);
        $this->incrementCityGroup($city, $gender);
        $this->incrementAgeGroup($age, $gender);
        $this->incrementOsGroup($os);
    }

    private function getAgeGroup(string $age): string {
        foreach (self::AGE_GROUPS as $group => [$min, $max]) {
            if ($age >= $min && $age <= $max) {
                return $group;
            }
        }
        return 'Other';
    } 
}
