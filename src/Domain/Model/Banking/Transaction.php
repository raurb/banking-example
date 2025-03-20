<?php

declare(strict_types=1);

namespace Raurb\BankingExample\Domain\Model\Banking;

use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;
use Raurb\BankingExample\Domain\ValueObject\Banking\BankAccountId;
use Raurb\BankingExample\Domain\ValueObject\Banking\Currency;
use Raurb\BankingExample\Domain\Enum\Banking\TransactionType;

readonly class Transaction
{
    private UuidInterface $transactionId;

    public function __construct(
        public BankAccountId $bankAccountId,
        public float $amount,
        public Currency $currency,
        public TransactionType $transactionType,
        public \DateTimeImmutable $transactionDate,
    ) {
        $this->transactionId = Uuid::uuid4();
    }
}