<?php

namespace Zinio\Tests\TravelMan\Services;

use PHPUnit\Framework\TestCase;
use Zinio\Domain\Cities\Model\City;
use Zinio\Domain\TravelMan\Exceptions\FirstCityIsntBeijingException;
use Zinio\Domain\TravelMan\Services\CalculatePathService;

class CalculatePathServiceTest extends TestCase
{
    private CalculatePathService $calculatePathService;

    protected function setUp()
    {
        $this->calculatePathService = new CalculatePathService();
    }

    public function testFirstCityIsBeijing()
    {
        $city1 = new City('Beijing', 39.93, 116.40);
        $response = $this->calculatePathService->calculate([ $city1 ]);
        
        $travelManPath = $response->path();
        $firstCity = $travelManPath[0];

        $this->assertEquals($city1, $firstCity);
    }

    public function testFirstCityIsntBeijingException()
    {
        $this->expectException(FirstCityIsntBeijingException::class);

        $city = new City("Tokyo", 35.40, 139.45);
        $this->calculatePathService->calculate([ $city ]);
    }

    public function testFindNearestFirstNode()
    {
        $city1 = new City('Beijing', 39.93, 116.40);
        $city2 = new City("Tokyo", 35.40, 139.45);

        $citiesList = [ $city1, $city2 ];
        $response = $this->calculatePathService->calculate($citiesList);
        
        $travelManPath = $response->path();

        $this->assertEquals($city1, $travelManPath[0]);
        $this->assertEquals($city2, $travelManPath[1]);
    }

    public function testBestPath()
    {
        $city1 = new City('Beijing', 39.93, 116.40);
        $city2 = new City("Tokyo", 35.40, 139.45);
        $city3 = new City('Vladivostok', 43.8, 131.54);
        $city4 = new City('Dakar', 14.40, -17.28);
        $city5 = new City('Singapore', 1.14, 103.55);

        $citiesList = [ $city1, $city2, $city3, $city4, $city5 ];
        $response = $this->calculatePathService->calculate($citiesList);
        
        $travelManPath = $response->path();

        $this->assertEquals($city1, $travelManPath[0]);
        $this->assertEquals($city3, $travelManPath[1]);
        $this->assertEquals($city2, $travelManPath[2]);
        $this->assertEquals($city5, $travelManPath[3]);
        $this->assertEquals($city4, $travelManPath[4]);
    }

    public function testBestPathWithForesight()
    {
        $city1 = new City('Beijing', 39.93, 116.40);
        $city2 = new City("Tokyo", 35.40, 139.45);
        $city3 = new City('Vladivostok', 43.8, 131.54);
        $city4 = new City('Dakar', 14.40, -17.28);
        $city5 = new City('Singapore', 1.14, 103.55);
        $city6 = new City('San Francisco', 37.47, -122.26);
        $city7 = new City('Auckland', -36.52, 174.45);
        $city8 = new City('London', 51.32, -0.5);
        $city9 = new City('Reykjavík', 64.4, -21.58);
        $city10 = new City('Paris', 48.86, 2.34);

        $citiesList = [ $city1, $city2, $city3, $city4, $city5,$city6, $city7, $city8, $city9, $city10 ];
        $maxNodesToForesight = 1;
        $response = $this->calculatePathService->calculate($citiesList, $maxNodesToForesight);

        $travelManPath = $response->path();

        $this->assertEquals($city1, $travelManPath[0]);
        $this->assertEquals($city3, $travelManPath[1]);
        $this->assertEquals($city2, $travelManPath[2]);
        $this->assertEquals($city7, $travelManPath[3]);
        $this->assertEquals($city5, $travelManPath[4]);
        $this->assertEquals($city4, $travelManPath[5]);
        $this->assertEquals($city6, $travelManPath[6]);
        $this->assertEquals($city8, $travelManPath[7]);
        $this->assertEquals($city10, $travelManPath[8]);
        $this->assertEquals($city9, $travelManPath[9]);

        $maxNodesToForesight = 2;
        $response = $this->calculatePathService->calculate($citiesList, $maxNodesToForesight);

        $travelManPath = $response->path();

        $this->assertEquals($city1, $travelManPath[0]);
        $this->assertEquals($city3, $travelManPath[1]);
        $this->assertEquals($city10, $travelManPath[2]);
        $this->assertEquals($city8, $travelManPath[3]);
        $this->assertEquals($city9, $travelManPath[4]);
        $this->assertEquals($city6, $travelManPath[5]);
        $this->assertEquals($city4, $travelManPath[6]);
        $this->assertEquals($city5, $travelManPath[7]);
        $this->assertEquals($city2, $travelManPath[8]);
        $this->assertEquals($city7, $travelManPath[9]);
    }

}