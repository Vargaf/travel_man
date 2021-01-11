<?php

namespace Zinio\Tests\TravelMan\Services;

use PHPUnit\Framework\TestCase;
use Zinio\Domain\TravelMan\Model\NormalizedNode;
use Zinio\Domain\TravelMan\Services\AdjacencyMatrixGeneratorService;

class AdjacencyMatrixGeneratorServiceTest extends TestCase
{
    public function testGeneratedAdjacencyMatrix()
    {
        $service = new AdjacencyMatrixGeneratorService();

        $normalizedNode1 = new NormalizedNode('Beijing', 4, 3, 0);
        $normalizedNode2 = new NormalizedNode('Tokyo', 3, 5, 1);
        $normalizedNode3 = new NormalizedNode('Vladivostok', 5, 4, 2);
        $normalizedNode4 = new NormalizedNode('Dakar', 2, 1, 3);
        $normalizedNode5 = new NormalizedNode('Singapore', 1, 2, 4);

        $adjacencyMatrixGenerated = $service->generate([ $normalizedNode1, $normalizedNode2, $normalizedNode3, $normalizedNode4, $normalizedNode5 ]);

        $adjacencyMatrixExpected = [ [0, 2, 1, 3, 4], [2, 0, 2, 6, 5], [1, 2, 0, 7, 8], [3, 6, 7, 0, 1], [4, 5, 8, 1, 0] ];

        $this->assertEquals($adjacencyMatrixExpected, $adjacencyMatrixGenerated);
    }
}