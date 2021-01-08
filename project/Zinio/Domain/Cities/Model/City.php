<?php

namespace Zinio\Domain\Cities\Model;

class City
{

    private string $name;
    private float $coordinateA;
    private float $coordinateB;
    
    public function __construct(string $name, float $coordinateA, float $coordinateB)
    {
        $this->name = $name;
        $this->coordinateA = $coordinateA;
        $this->coordinateB = $coordinateB;    
    }

    public function name(): string{
        return $this->name;
    }

    public function coordinateA(): float
    {
        return $this->coordinateA;
    }

    public function coordinateB(): float
    {
        return $this->coordinateB;
    }

}