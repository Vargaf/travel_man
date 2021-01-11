<?php

namespace Zinio\Domain\TravelMan\Services;

use Zinio\Domain\Cities\Model\City;
use Zinio\Domain\TravelMan\Model\TravelManPath;
use Zinio\Domain\TravelMan\Model\NormalizedNode;
use Zinio\Domain\TravelMan\Exceptions\FirstCityIsntBeijingException;

class CalculatePathService
{
    private const FIRST_CITY_NAME = 'Beijing';

    /** @var NormalizedNode[] $normalizedNodes */
    private array $normalizedNodes;

    /** @var City[] $citiesList */
    private array $citiesList;

    /** @var int[][] $adjacencyMatrix */
    private array $adjacencyMatrix;

    private TravelManPath $travelManPath;

    private $maxNodesToForesight;

    /**
     * @param City[] $citiesList
     * 
     * @throws FirstCityIsntBeijingException if the first city isn't Beijing
     */
    public function calculate(array $citiesList, int $maxNodesToForesight = 5): ?TravelManPath
    {
        $this->citiesList = $citiesList;
        $this->travelManPath = new TravelManPath();
        $this->normalizedNodes = NormalizeCoordinatesService::normailze($citiesList);
        $this->adjacencyMatrix = AdjacencyMatrixGeneratorService::generate($this->normalizedNodes);
        $this->maxNodesToForesight = $maxNodesToForesight;

        if(!$this->isBeijingTheFirstCity()) {
            throw new FirstCityIsntBeijingException();
        }

        $currentStartingNode = $this->normalizedNodes[0];
        $this->addNodeToTravelManPath($currentStartingNode);
        $this->removeRootNodeFromNormalizedNodeList();
        
        while(count($this->normalizedNodes) > 0) {
            $currentStartingNode = $this->findNearestCity($currentStartingNode);
            $this->addNodeToTravelManPath($currentStartingNode);
        }

        return $this->travelManPath;
    }

    private function removeRootNodeFromNormalizedNodeList()
    {
        array_splice($this->normalizedNodes, 0 ,1);
    }

    private function addNodeToTravelManPath(NormalizedNode $node)
    {
        $city = $this->citiesList[$node->positionInList()];
        $this->travelManPath->addCity($city);
    }

    private function isBeijingTheFirstCity(): bool
    {
        /** @var City $firstCity */
        $firstCity = $this->citiesList[0];
        return $firstCity->name() === self::FIRST_CITY_NAME;
    }

    private function findNearestCity(NormalizedNode $node): NormalizedNode
    {
        $combinations = $this->generateForesightCombinations($node);
        $cheapestPath = $this->getCheapestPath($combinations);
        $nearestNode = $cheapestPath[1];

        $nodeIndexToPrune = 0;
        foreach ($this->normalizedNodes as $nodeIndex => $node) {
            if($node->name() === $nearestNode->name()) {
                $nodeIndexToPrune = $nodeIndex;
                break;
            }
        }
        array_splice($this->normalizedNodes, $nodeIndexToPrune, 1);

        return $nearestNode;
    }

    /**
     * @param NormailzedNode[] $pathCombinations
     */
    private function getCheapestPath(array $pathList): array
    {
        $cheapestPathCost = 9999;
        $cheapestPath = [];

        foreach($pathList as $path) {
            $pathCost = 0;
            $pathLength = count($path);
            for ($curentNodeIndex=1; $curentNodeIndex < $pathLength; $curentNodeIndex++) { 
                $originNode = $path[$curentNodeIndex - 1];
                $destinyNode = $path[$curentNodeIndex];
                $pathCost += $this->getCostBetweenNodes($originNode, $destinyNode);
            }

            if($pathCost < $cheapestPathCost) {
                $cheapestPathCost = $pathCost;
                $cheapestPath = $path;
            }
        }

        return $cheapestPath;
    }

    private function generateForesightCombinations(NormalizedNode $currentNode) {
        $maxSpread = count($this->normalizedNodes);
        $maxSpread = $maxSpread < $this->maxNodesToForesight ? $maxSpread : $this->maxNodesToForesight;
        $acumulatedNodes = [];
        $foresightNodePathList[] = [$currentNode];

        for($currentSpread = 0; $currentSpread < $maxSpread; $currentSpread++) {
            $foresightNodeListNextLevel = [];

            foreach($foresightNodePathList as $foresightNodePath) {
                
                $acumulatedNodes = $this->foresightNearestNode(end($foresightNodePath), $foresightNodePath);
                
                foreach($acumulatedNodes as $node) {
                    $foresightNodeListNextLevel[] = array_merge($foresightNodePath, [$node]);
                }
            }

            if(count($acumulatedNodes)) {
                $foresightNodePathList = $foresightNodeListNextLevel;
            }
        }

        return $foresightNodePathList;
        
    }

    /**
     * @return NormalizedNode[]
     */
    private function foresightNearestNode(NormalizedNode $currentNode, array $acumulatedNodes = []): ?array
    {   
        $maxSpread = count($this->normalizedNodes);
        $maxSpread = $maxSpread < $this->maxNodesToForesight ? $maxSpread : $this->maxNodesToForesight;
        $currentDepth = count($acumulatedNodes) - 1;
        $alreadyVisitedList = [];
        
        if($currentDepth < $maxSpread) {
            for($currentSpread = 0; $currentSpread < $maxSpread; $currentSpread++) {
                $newForesightNode = $this->findNearestNode($currentNode, $acumulatedNodes);
                if($newForesightNode) {
                    $acumulatedNodes[] = $newForesightNode;
                    $alreadyVisitedList[] = $newForesightNode;
                } else {
                    break;
                }
            }
        }

        return $alreadyVisitedList;
        
    }
    private function findNearestNode(NormalizedNode $node, array $acumulatedNodes): ?NormalizedNode
    {
        if($this->areAllNodesChecked($acumulatedNodes)) {
            return null;
        }

        $nearestNode = null;
        $radarLength = 1;

        do {
            $coordinateX = $node->coordinateA();
            $coordinateY = $node->coordinateB();
            $minX = $this->curateAxisCoordinate($coordinateX - $radarLength);
            $maxX = $this->curateAxisCoordinate($coordinateX + $radarLength);
            $minY = $this->curateAxisCoordinate($coordinateY - $radarLength);
            $maxY = $this->curateAxisCoordinate($coordinateY + $radarLength);
            
            $nearestNodeList = [];
            foreach($this->normalizedNodes as $destinyNode) {
                if($this->isDestinyNodeInsideRadar($minX, $maxX, $minY, $maxY, $destinyNode->coordinateA(), $destinyNode->coordinateB())) {
                    $alreadyVisited = false;
                    
                    foreach($acumulatedNodes as $nodeAlreadyVisited) {
                        if($nodeAlreadyVisited->name() === $destinyNode->name()) {
                            $alreadyVisited = true;
                        }
                    }
                    if(!$alreadyVisited) {
                        $nearestNodeList[] = $destinyNode;
                    }
                }
            }

            $cheapestPathCost = 9999;
            foreach($nearestNodeList as $destinyNode) {
                $currentPathCost = $this->getCostBetweenNodes($node, $destinyNode);
                if($currentPathCost < $cheapestPathCost) {
                    $nearestNode = $destinyNode;
                    $cheapestPathCost = $currentPathCost;
                }
            }

            $radarLength++;

        } while(!$nearestNode);

        return $nearestNode;
    }

    private function areAllNodesChecked(array $acumulatedNodes): bool
    {
        return count($acumulatedNodes) > count($this->normalizedNodes);
    }

    private function getCostBetweenNodes(NormalizedNode $origin, NormalizedNode $destiny): int
    {
        return $this->adjacencyMatrix[$origin->positionInList()][$destiny->positionInList()];
    }

    private function isDestinyNodeInsideRadar(int $minX, int $maxX, int $minY, int $maxY, int $coordinateX, int $coordinateY): bool
    {
        return $minX <= $coordinateX && $coordinateX <= $maxX &&
            $minY <= $coordinateY && $coordinateY <= $maxY;
    }

    private function curateAxisCoordinate(int $value): int
    {
        $maxBoundary = count($this->citiesList);

        $value = $value < 0 ? 0 : $value;
        $value = $maxBoundary < $value ? $maxBoundary : $value;

        return $value;
    }
}