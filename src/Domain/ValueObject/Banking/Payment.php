<?php

declare(strict_types=1);

namespace Raurb\BankingExample\Domain\ValueObject\Banking;

readonly class Payment
{
    public function __construct(
        public float $amount,
        public Currency $currency,
    ) {
        $this->validateAmount($amount);
    }

    private function validateAmount(float $amount): void
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Payment amount cannot be lover than 0');
        }
    }
}