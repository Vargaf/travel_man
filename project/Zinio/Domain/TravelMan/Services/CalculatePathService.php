<?php

namespace Zinio\Domain\TravelMan\Services;

use Zinio\Domain\Cities\Model\City;
use Zinio\Domain\TravelMan\Exceptions\FirstCityIsntBeijingException;
use Zinio\Domain\TravelMan\Model\TravelManPath;

class CalculatePathService
{
    private const FIRST_CITY_NAME = 'Beijing';

    /**
     * @param City[] $citiesList
     * 
     * @throws FirstCityIsntBeijingException if the first city isn't Beijing
     */
    public function calculate(array $citiesList): ?TravelManPath
    {
        $travelManPath = new TravelManPath();

        if($this->isBeijingTheFirstCity($citiesList)) {
            $travelManPath->addCity($citiesList[0]);
        } else {
            throw new FirstCityIsntBeijingException();
        }
        
        return $travelManPath;
    }

    /**
     * @param City[] $citiesList
     */
    private function isBeijingTheFirstCity(array $citiesList): bool
    {
        /** @var City $firstCity */
        $firstCity = $citiesList[0];
        return $firstCity->name() === self::FIRST_CITY_NAME;
    }
}