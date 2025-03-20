<?php

declare(strict_types=1);

namespace Raurb\BankingExample\Domain\ValueObject\Banking;

readonly class AccountDetails
{
    public function __construct(
        public Currency $baseCurrency,
        public float $transactionFee = 0,
    ) {
        $this->validateTransactionFee($transactionFee);
    }

    private function validateTransactionFee(float $transactionFee): void
    {
        if ($transactionFee < 0) {
            throw new \InvalidArgumentException('Transaction fee cannot be lover than 0');
        }
    }
}