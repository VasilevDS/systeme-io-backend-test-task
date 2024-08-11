<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

final class PaymentProcessorException extends Exception
{
    public function __construct(
        string $message = 'Payment processing error',
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, previous: $previous);
    }
}
