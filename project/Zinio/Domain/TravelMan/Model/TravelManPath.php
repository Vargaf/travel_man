<?php

namespace Zinio\Domain\TravelMan\Model;

use Zinio\Domain\Cities\Model\City;

class TravelManPath
{
    private array $path = [];

    public function addCity(City $city): TravelManPath
    {
        $this->path[] = new City($city->name(), $city->coordinateA(), $city->coordinateB());
        return $this;
    }

    public function path(): array
    {
        return $this->path;
    }

    public function getPathCost(): float
    {
        $pathLength = count($this->path);
        $totalCost = 0;

        for ($nodeIndex=1; $nodeIndex < $pathLength; $nodeIndex++) { 
            $originNode = $this->path[$nodeIndex - 1];
            $destinyNode = $this->path[$nodeIndex];

            $totalCost += sqrt(pow($destinyNode->coordinateA() - $originNode->coordinateA(), 2) + pow($destinyNode->coordinateB() - $originNode->coordinateB(), 2));
        }

        return $totalCost;
    }
}