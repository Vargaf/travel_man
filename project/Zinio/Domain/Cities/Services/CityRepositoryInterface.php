<?php

namespace Zinio\Domain\Cities\Services;

interface CityRepositoryInterface
{
    /** @return City[] */
    public function loadCities(): array;
}