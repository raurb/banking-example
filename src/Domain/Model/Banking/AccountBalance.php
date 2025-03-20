<?php

declare(strict_types=1);

namespace Raurb\BankingExample\Domain\Model\Banking;

use Raurb\BankingExample\Domain\Enum\Banking\TransactionType;
use Raurb\BankingExample\Domain\ValueObject\Banking\BankAccountId;
use Raurb\BankingExample\Domain\ValueObject\Banking\Currency;

class AccountBalance
{
    public function __construct(
        public readonly BankAccountId $bankAccountId,
        private float $balance = 0.0,
        /** @var Transaction[] $creditTransactions */
        private array $creditTransactions = [],
        /** @var Transaction[] $debitTransactions */
        private array $debitTransactions = [],
    ) {}

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function registerCredit(float $amount, Currency $currency): void
    {
        $this->creditTransactions[] = new Transaction(
            $this->bankAccountId, 
            $amount, 
            $currency, 
            TransactionType::CREDIT, 
            new \DateTimeImmutable()
        );

        $this->balance = \round($this->balance + $amount, 2);

    }

    public function registerDebit(float $amount, Currency $currency): void
    {
        $this->debitTransactions[] = new Transaction(
            $this->bankAccountId, 
            $amount, 
            $currency, 
            TransactionType::DEBIT, 
            new \DateTimeImmutable()
        );

        $this->balance = \round($this->balance - $amount, 2);
    }

    public function getTransactionsByDate(\DateTimeImmutable $date, ?TransactionType $transactionType = null): array
    {
        $foundTransactions = [];
        
        if ($transactionType === TransactionType::DEBIT) {
            foreach ($this->debitTransactions as $debitTransaction) {
                if ($debitTransaction->transactionDate->format('Y-m-d') === $date->format('Y-m-d')) {
                    $foundTransactions[] = $debitTransaction;
                }
            }

            return $foundTransactions;
        }

        if ($transactionType === TransactionType::CREDIT) {
            foreach ($this->creditTransactions as $creditTransaction) {
                if ($creditTransaction->transactionDate->format('Y-m-d') === $date->format('Y-m-d')) {
                    $foundTransactions[] = $creditTransaction;
                }
            }

            return $foundTransactions;
        }

        foreach (\array_merge($this->creditTransactions, $this->debitTransactions) as $transaction) {
            if ($transaction->transactionDate->format('Y-m-d') === $date->format('Y-m-d')) {
                $foundTransactions[] = $transaction;
            }
        }

        return $foundTransactions;
    }
}