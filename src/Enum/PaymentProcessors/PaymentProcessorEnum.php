<?php

declare(strict_types=1);

namespace App\Enum\PaymentProcessors;

enum PaymentProcessorEnum: string
{
    case Paypal = 'paypal';
    case Stripe = 'stripe';
}
