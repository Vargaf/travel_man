<?php

namespace Zinio\Domain\TravelMan\Services;

use Zinio\Domain\Cities\Model\City;
use Zinio\Domain\TravelMan\Model\NormalizedNode;

class NormalizeCoordinatesService
{
    /**
     * @param City[] $citiesList
     * 
     * @return NormalizedNode[]
     */
    public static function normailze(array $citiesList): array
    {
        /** @var NormalizedNode[] $nodeList */
        $nodeList = [];

        $sortedACordinateList = self::sortACordinates($citiesList);
        $sortedBCordinateList = self::sortBCordinates($citiesList);

        /** @var City $city */    
        foreach($citiesList as $cityIndex => $city) {
            $normalizedACoordinate = array_search($city->coordinateA(), $sortedACordinateList) + 1;
            $normalizedBCoordinate = array_search($city->coordinateB(), $sortedBCordinateList) + 1;

            $normalizedNodePath = new NormalizedNode($city->name(), $normalizedACoordinate, $normalizedBCoordinate, $cityIndex);
            $nodeList[] = $normalizedNodePath;
        }

        return $nodeList;
    }

    /**
     * @param City[] $citiesList
     * 
     * @return float[]
     */
    private static function sortACordinates(array $citiesList): array
    {
        $coordinateList = [];
        
        foreach($citiesList as $city) {
            /** @var City $city */    
            $coordinateList[] = $city->coordinateA();
        }

        sort($coordinateList);

        return $coordinateList;
    }

    /**
     * @param City[] $citiesList
     * 
     * @return float[]
     */
    private static function sortBCordinates(array $citiesList): array
    {
        $coordinateList = [];
        
        foreach($citiesList as $city) {
            /** @var City $city */    
            $coordinateList[] = $city->coordinateB();
        }

        sort($coordinateList);

        return $coordinateList;
    }

}