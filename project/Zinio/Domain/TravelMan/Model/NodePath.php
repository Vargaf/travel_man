<?php

namespace Zinio\Domain\TravelMan\Model;

class NodePath
{
    private string $name;
    private int $coordinateA;
    private int $coordinateB;

    public function __construct(string $name, int $coordinateA, int $coordinateB)
    {
        $this->name = $name;
        $this->coordinateA = $coordinateA;
        $this->coordinateB = $coordinateB;
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

}