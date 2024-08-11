<?php

declare(strict_types=1);

namespace App\Service\PaymentProcessors;

use App\Exception\PaymentProcessorException;

interface PaymentProcessorInterface
{
    /**
     * @throws PaymentProcessorException
     */
    public function purchase(int $priceInCent): void;
}
