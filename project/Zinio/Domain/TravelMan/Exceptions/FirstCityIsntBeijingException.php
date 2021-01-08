<?php

namespace Zinio\Domain\TravelMan\Exceptions;

class FirstCityIsntBeijingException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The first city to start the journey must be Beijing');   
    }
}