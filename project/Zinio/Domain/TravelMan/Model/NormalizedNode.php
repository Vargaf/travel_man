<?php

namespace Zinio\Domain\TravelMan\Model;

class NormalizedNode
{
    private string $name;
    private int $coordinateA;
    private int $coordinateB;
    private int $positionInList;

    public function __construct(string $name, int $coordinateA, int $coordinateB, int $positionInList)
    {
        $this->name = $name;
        $this->coordinateA = $coordinateA;
        $this->coordinateB = $coordinateB;
        $this->positionInList = $positionInList;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function coordinateA(): int
    {
        return $this->coordinateA;
    }

    public function coordinateB(): int
    {
        return $this->coordinateB;
    }

    public function positionInList(): int
    {
        return $this->positionInList;
    }

}