<?php

namespace Zinio\Tests\TravelMan\Services;

use PHPUnit\Framework\TestCase;
use Zinio\Domain\Cities\Model\City;
use Zinio\Domain\TravelMan\Model\NormalizedNode;
use Zinio\Domain\TravelMan\Services\NormalizeCoordinatesService;

class NormailzeCoordinatesServiceTest extends TestCase
{
    public function testNormalizeFirstNode()
    {
        $city1 = new City('Beijing', 39.93, 116.40);

        $service = new NormalizeCoordinatesService();
        /** @var NodePath[] $response */
        $response = $service->normailze([ $city1 ]);

        $expectedNode = new NormalizedNode('Beijing', 1, 1, 0);
        $this->assertEquals($expectedNode, $response[0]);
    }

    public function testNormalizeAFewNodes()
    {
        $city1 = new City('Beijing', 39.93, 116.40);
        $city2 = new City('Tokyo', 35.40, 139.45);
        $city3 = new City('Vladivostok', 43.8, 131.54);
        $city4 = new City('Dakar', 14.40, -17.28);
        $city5 = new City('Singapore', 1.14, 103.55);

        $service = new NormalizeCoordinatesService();
        /** @var NodePath[] $response */
        $response = $service->normailze([ $city1, $city2, $city3, $city4, $city5 ]);

        $expectedNode1 = new NormalizedNode('Beijing', 4, 3, 0);
        $expectedNode2 = new NormalizedNode('Tokyo', 3, 5, 1);
        $expectedNode3 = new NormalizedNode('Vladivostok', 5, 4, 2);
        $expectedNode4 = new NormalizedNode('Dakar', 2, 1, 3);
        $expectedNode5 = new NormalizedNode('Singapore', 1, 2, 4);
        
        $this->assertEquals($expectedNode1, $response[0]);
        $this->assertEquals($expectedNode2, $response[1]);
        $this->assertEquals($expectedNode3, $response[2]);
        $this->assertEquals($expectedNode4, $response[3]);
        $this->assertEquals($expectedNode5, $response[4]);
    }
}