<?php

namespace Zinio\Domain\TravelMan\Application;

use Zinio\Domain\TravelMan\Model\TravelManPath;
use Zinio\Domain\Cities\Services\CityRepositoryInterface;
use Zinio\Domain\TravelMan\Services\CalculatePathService;
use Zinio\Domain\TravelMan\Exceptions\FirstCityIsntBeijingException;
use Zinio\Domain\TravelMan\Exceptions\TravelManGetShortestPathException;

class TravelManGetShortestPathUseCase
{
    private CityRepositoryInterface $cityRepository;
    private CalculatePathService $calculatePathService;

    public function __construct(
        CityRepositoryInterface $cityRepository,
        CalculatePathService $calculatePathService)
    {
        $this->cityRepository = $cityRepository;
        $this->calculatePathService = $calculatePathService;
    }

    /**
     * @throws TravelManGetShortestPathException if something went wrong
     */
    public function execute(int $maxNodesToForesight): TravelManPath
    {
        try {
            
            $citiesList = $this->cityRepository->loadCities();
            $maxNodesToForesight = $maxNodesToForesight;
            $travelManPath = $this->calculatePathService->calculate($citiesList, $maxNodesToForesight);

        } catch (FirstCityIsntBeijingException $error) {
            throw new TravelManGetShortestPathException($error->getMessage());
        } catch (\Exception $error) {
            throw new TravelManGetShortestPathException($error->getMessage());
        }

        return $travelManPath;
    }
}
