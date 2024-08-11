<?php

declare(strict_types=1);

namespace App\Service\PaymentProcessors;

use App\Enum\PaymentProcessors\PaymentProcessorEnum;
use App\Exception\PaymentProcessorException;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

#[AutoconfigureTag(name: 'payment_processor')]
#[AsTaggedItem(index: PaymentProcessorEnum::Paypal->value)]
final class PayPalPaymentProcessorAdapter extends PaypalPaymentProcessor implements PaymentProcessorInterface
{
    #[Override]
    public function purchase(int $priceInCent): void
    {
        try {
            $this->pay($priceInCent);
        } catch (\Throwable $exception) {
            throw new PaymentProcessorException($exception->getMessage(), $exception);
        }
    }
}
