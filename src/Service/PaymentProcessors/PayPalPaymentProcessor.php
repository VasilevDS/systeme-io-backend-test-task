<?php

declare(strict_types=1);

namespace App\Service\PaymentProcessors;

use App\Enum\PaymentProcessors\PaymentProcessorEnum;
use App\Exception\PaymentProcessorException;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as SystemeioPaypalPaymentProcessor;

#[AutoconfigureTag(name: 'payment_processor')]
#[AsTaggedItem(index: PaymentProcessorEnum::Paypal->value)]
final readonly class PayPalPaymentProcessor implements PaymentProcessorInterface
{
    private SystemeioPaypalPaymentProcessor $paypalPaymentProcessor;

    public function __construct() {
        $this->paypalPaymentProcessor = new SystemeioPaypalPaymentProcessor();
    }

    #[Override]
    public function purchase(int $priceInCent): void
    {
        try {
            $this->paypalPaymentProcessor->pay($priceInCent);
        } catch (\Throwable $exception) {
            throw new PaymentProcessorException($exception->getMessage(), $exception);
        }
    }
}
