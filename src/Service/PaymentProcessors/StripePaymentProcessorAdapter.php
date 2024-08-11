<?php

declare(strict_types=1);

namespace App\Service\PaymentProcessors;

use App\Enum\PaymentProcessors\PaymentProcessorEnum;
use App\Exception\PaymentProcessorException;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;
use Throwable;

#[AutoconfigureTag(name: 'payment_processor')]
#[AsTaggedItem(index: PaymentProcessorEnum::Stripe->value)]
final class StripePaymentProcessorAdapter extends StripePaymentProcessor implements PaymentProcessorInterface
{
    #[Override]
    public function purchase(int $priceInCent): void
    {
        $price = $priceInCent / 100;

        try {
            $result = $this->processPayment($price);
        } catch (Throwable $exception) {
            throw new PaymentProcessorException($exception->getMessage(), $exception);
        }

        if ($result === false) {
            throw new PaymentProcessorException();
        }
    }
}
