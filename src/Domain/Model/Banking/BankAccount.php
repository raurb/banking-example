<?php

declare(strict_types=1);

namespace Raurb\BankingExample\Domain\Model\Banking;

use Ramsey\Uuid\Nonstandard\Uuid;
use Raurb\BankingExample\Domain\Enum\Banking\TransactionType;
use Raurb\BankingExample\Domain\ValueObject\Banking\AccountDetails;
use Raurb\BankingExample\Domain\ValueObject\Banking\BankAccountId;
use Raurb\BankingExample\Domain\ValueObject\Banking\Currency;
use Raurb\BankingExample\Domain\ValueObject\Banking\Payment;

class BankAccount 
{
    private const int DAILY_DEBIT_TRANSACTIONS_LIMIT = 3;

    public function __construct(
        private readonly BankAccountId $bankAccountId,
        private readonly AccountDetails $accountDetails,
        private readonly AccountBalance $accountBalance,
    ) {}

    public static function create(string $currency, float $transactionFee): self
    {
        $newAccountId = new BankAccountId(Uuid::uuid4());
        return new self(
            $newAccountId,
            new AccountDetails(new Currency($currency), $transactionFee),
            new AccountBalance($newAccountId),
        );
    }

    public function credit(Payment $payment): void
    {
        $this->validateCurrency($payment->currency);
        $this->accountBalance->registerCredit($payment->amount, $payment->currency);
    }

    public function debit(Payment $payment): void
    {
        $this->validateCurrency($payment->currency);
        $paymentAmountWithFee = $this->calculateDebitAmountWithFee($payment->amount, $this->accountDetails->transactionFee);

        if (!$this->isBalanceSufficientForDebit($paymentAmountWithFee)) {
            throw new \InvalidArgumentException('Account balance is not sufficient enough to make debit payment.');
        }
        
        if ($this->isDebitDailyTransactionLimitExceeded()) {
            throw new \InvalidArgumentException(\sprintf('Account exceeded daily limit of debit payments of %d', self::DAILY_DEBIT_TRANSACTIONS_LIMIT));
        }

        $this->accountBalance->registerDebit($paymentAmountWithFee, $payment->currency);
    }

    public function getBalance(): float
    {
        return $this->accountBalance->getBalance();
    }

    private function validateCurrency(Currency $paymentCurrency): void
    {
        if (!$this->accountDetails->baseCurrency->isSame($paymentCurrency)) {
            throw new \InvalidArgumentException(
                \sprintf('Payment currency (%s) is different than account\'s base currency (%s)', $paymentCurrency->currency, $this->accountDetails->baseCurrency->currency)
            );
        }
    }

    private function isBalanceSufficientForDebit(float $amount): bool
    {
        return $this->accountBalance->getBalance() >= $amount;
    }

    private function isDebitDailyTransactionLimitExceeded(): bool
    {
        return \count($this->accountBalance->getTransactionsByDate(new \DateTimeImmutable(), TransactionType::DEBIT)) >= self::DAILY_DEBIT_TRANSACTIONS_LIMIT;
    }

    private function calculateDebitAmountWithFee(float $paymentAmount, float $feePercentage): float
    {
        return $paymentAmount * (1 + $feePercentage);
    }
}