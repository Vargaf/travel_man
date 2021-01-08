<?php

namespace Zinio\Tests\TravelMan\Services;

use PHPUnit\Framework\TestCase;
use Zinio\Domain\Cities\Model\City;
use Zinio\Domain\TravelMan\Model\NodePath;
use Zinio\Domain\TravelMan\Services\NormalizeCoordinatesService;

class NormailzeCoordinatesServiceTest extends TestCase
{
    public function testNormalizeFirstNode()
    {
        $city1 = new City('Beijing', 39.93, 116.40);

        $service = new NormalizeCoordinatesService();
        /** @var NodePath[] $response */
        $response = $service->normailze([ $city1 ]);

        $expectedNode = new NodePath('Beijing', 1, 1);
        $this->assertEquals($expectedNode, $response[0]);
    }

    public function testNormalizeAFewNodes()
    {
        $city1 = new City('Beijing', 39.93, 116.40);
        $ciry2 = new City('Tokyo', 35.40, 139.45);
        $city3 = new City('Vladivostok', 43.8, 131.54);
        $city4 = new City('Dakar', 14.40, -17.28);
        $city5 = new City('Singapore', 1.14, 103.55);


        $service = new NormalizeCoordinatesService();
        /** @var NodePath[] $response */
        $response = $service->normailze([ $city1, $ciry2, $city3, $city4, $city5 ]);

        $expectedNode1 = new NodePath('Beijing', 4, 3);
        $expectedNode2 = new NodePath('Tokyo', 3, 5);
        $expectedNode3 = new NodePath('Vladivostok', 5, 4);
        $expectedNode4 = new NodePath('Dakar', 2, 1);
        $expectedNode5 = new NodePath('Singapore', 1, 2);
        
        $this->assertEquals($expectedNode1, $response[0]);
        $this->assertEquals($expectedNode2, $response[1]);
        $this->assertEquals($expectedNode3, $response[2]);
        $this->assertEquals($expectedNode4, $response[3]);
        $this->assertEquals($expectedNode5, $response[4]);

    }
}