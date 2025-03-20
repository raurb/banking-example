<?php

declare(strict_types=1);

namespace Raurb\BankingExample\Domain\ValueObject\Banking;

readonly class Currency
{
    public function __construct(public string $currency) {
        $this->validateCurrency($currency);
    }

    public function isSame(Currency $other): bool
    {
        return $this->currency === $other->currency;
    }

    private function validateCurrency(string $currency): void
    {
        if (!$currency || \strlen($currency) !== 3) {
            throw new \InvalidArgumentException('Invalid currency provided.');
        }
    }
}