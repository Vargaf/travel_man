<?php

namespace Zinio\Domain\TravelMan\Exceptions;

class TravelManGetShortestPathException extends \Exception
{
    public function __construct($message = 'Something went wrong')
    {
        parent::__construct($message);;
    }
}
