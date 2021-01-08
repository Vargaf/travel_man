<?php

namespace Zinio\Infrastructure\TravelMan;

use Zinio\Domain\Cities\Model\City;
use Zinio\Domain\Cities\Services\CityRepositoryInterface;

class InFileCityRepositoryService implements CityRepositoryInterface
{
    /** @return City[] */
    public function loadCities(): array
    {
        /** @var City[] $citiesList */
        $citiesList = [];

        $file = \fopen('../db/cities.txt', 'r');

        while(!\feof($file)) {
            $line = \fgetcsv($file, 0, "\t");
            if($line) {
                $city = new City($line[0], $line[1], $line[2]);
                $citiesList[] = $city;
            }
        }

        \fclose($file);

        return $citiesList;
    }

}
