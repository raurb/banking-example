<?php

declare(strict_types=1);

namespace Raurb\BankingExample\Domain\ValueObject\Banking;

use Ramsey\Uuid\UuidInterface;

readonly class BankAccountId
{
    public function __construct(UuidInterface $bankAccountId){}
}