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

}