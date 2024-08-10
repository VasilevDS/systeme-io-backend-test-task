<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class CalculateCostNegativeValueException extends Exception
{
    public function __construct(string $message = 'Negative price')
    {
        parent::__construct($message);
    }
}
