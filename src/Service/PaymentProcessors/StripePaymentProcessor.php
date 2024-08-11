<?php

declare(strict_types=1);

namespace App\Service\PaymentProcessors;

use App\Enum\PaymentProcessors\PaymentProcessorEnum;
use App\Exception\PaymentProcessorException;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as SystemeioStripePaymentProcessor;
use Throwable;

#[AutoconfigureTag(name: 'payment_processor')]
#[AsTaggedItem(index: PaymentProcessorEnum::Stripe->value)]
final readonly class StripePaymentProcessor implements PaymentProcessorInterface
{
    private SystemeioStripePaymentProcessor $stripePaymentProcessor;

    public function __construct() {
        $this->stripePaymentProcessor = new SystemeioStripePaymentProcessor();
    }

    #[Override]
    public function purchase(int $priceInCent): void
    {
        $price = $priceInCent / 100;

        try {
            $result = $this->stripePaymentProcessor->processPayment($price);
        } catch (Throwable $exception) {
            throw new PaymentProcessorException($exception->getMessage(), $exception);
        }

        if ($result === false) {
            throw new PaymentProcessorException();
        }
    }
}
