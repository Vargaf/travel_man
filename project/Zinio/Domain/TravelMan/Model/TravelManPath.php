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
}