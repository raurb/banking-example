<?php

declare(strict_types=1);

namespace Raurb\BankingExample\Domain\Enum\Banking;

enum TransactionType: string
{
    case DEBIT = 'DEBIT';
    case CREDIT = 'CREDIT';
}