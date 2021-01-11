<?php

namespace Zinio\Domain\TravelMan\Services;

use Zinio\Domain\TravelMan\Model\NormalizedNode;

class AdjacencyMatrixGeneratorService
{
    /**
     * @param NormalizedNode[] $normailzedNodePath
     */
    public static function generate(array $normailzedNodePath): array
    {
        $nodeMatrix = [];
        for ($y=0; $y < 5; $y++) { 
            $nodeMatrix[$y] = [];

            for ($x=0; $x < 5; $x++) { 
                $nodeMatrix[$y][$x] = "";
            }
        }

        foreach ($normailzedNodePath as $node) {
            $nodeMatrix[$node->coordinateB() - 1][$node->coordinateA() - 1] = $node->name();
        }

        $adjacencyMatrix = [];
        $nodeWightList = [];
        $numberOfCities = count($normailzedNodePath);
        for ($cityRowNumber=0; $cityRowNumber < $numberOfCities; $cityRowNumber++) { 
            $nodeRow = $normailzedNodePath[$cityRowNumber];

            $adjacencyMatrix[$cityRowNumber] = [];

            for ($cityColNumber=0; $cityColNumber < $numberOfCities; $cityColNumber++) { 
                $nodeCol = $normailzedNodePath[$cityColNumber];
    
                $nodeDistance = sqrt(pow($nodeCol->coordinateA() - $nodeRow->coordinateA(), 2) + pow($nodeCol->coordinateB() - $nodeRow->coordinateB(), 2));
                $adjacencyMatrix[$cityRowNumber][$cityColNumber] = $nodeDistance;
                $nodeWightList[] = $nodeDistance;
            }
        }

        $nodeWightList = array_unique($nodeWightList);
        sort($nodeWightList);

        for ($cityRowNumber=0; $cityRowNumber < $numberOfCities; $cityRowNumber++) { 
            for ($cityColNumber=0; $cityColNumber < $numberOfCities; $cityColNumber++) { 
                $normalizedWeightIndex = array_search($adjacencyMatrix[$cityRowNumber][$cityColNumber], $nodeWightList);
                $adjacencyMatrix[$cityRowNumber][$cityColNumber] = $normalizedWeightIndex;
            }
        }

        return $adjacencyMatrix;
    }
}