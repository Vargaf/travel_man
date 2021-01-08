<?php

namespace Zinio\Domain\TravelMan\Services;

use Zinio\Domain\Cities\Model\City;
use Zinio\Domain\TravelMan\Model\NodePath;

class NormalizeCoordinatesService
{
    /**
     * @var int $sortedACordinateList
     */
    private array $sortedACordinateList = [];

    /**
     * @var int $sortedBCordinateList
     */
    private array $sortedBCordinateList = [];

    /**
     * @param City[] $citiesList
     * 
     * @return NodePath[]
     */
    public function normailze(array $citiesList): array
    {
        /** @var NodePath[] $nodeList */
        $nodeList = [];

        $sortedACordinateList = $this->sortACordinates($citiesList);
        $sortedBCordinateList = $this->sortBCordinates($citiesList);

        /** @var City $city */    
        foreach($citiesList as $city) {
            $normalizedACoordinate = array_search($city->coordinateA(), $sortedACordinateList) + 1;
            $normalizedBCoordinate = array_search($city->coordinateB(), $sortedBCordinateList) + 1;

            $nodePath = new NodePath($city->name(), $normalizedACoordinate, $normalizedBCoordinate);
            $nodeList[] = $nodePath;
        }

        return $nodeList;
    }

    /**
     * @param City[] $citiesList
     * 
     * @return float[]
     */
    private function sortACordinates(array $citiesList): array
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
    private function sortBCordinates(array $citiesList): array
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