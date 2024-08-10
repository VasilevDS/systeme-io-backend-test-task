<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class TaxNumberFormatException extends Exception
{
    public function __construct(string $message = 'Incorrect tax number format.')
    {
        parent::__construct($message, 400);
    }
}
