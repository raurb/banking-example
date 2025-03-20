<?php

use PHPUnit\Framework\TestCase;
use Raurb\BankingExample\Domain\Model\Banking\BankAccount;
use Raurb\BankingExample\Domain\ValueObject\Banking\Currency;
use Raurb\BankingExample\Domain\ValueObject\Banking\Payment;
use function PHPUnit\Framework\assertEquals;

class BankAccountTest extends TestCase
{
    /**
     * I should have written more precise Exceptions for better error handling, 
     * but I had no more time for that...
     * Also, I am aware that some tests are missing, but I am short on time
     */

    private BankAccount $bankAccount;

    public function setUp(): void
    {
        $this->bankAccount = BankAccount::create('PLN', 0.005);
    }

    public function testCreate(): void
    {
        $bankAccount = BankAccount::create('PLN', 0.005);
        $this->assertEquals(0, $bankAccount->getBalance());

        $this->expectException(\InvalidArgumentException::class);
        $bankAccount = BankAccount::create('TEST', 0.005);
    }

    public function testCredit(): void
    {
        $this->bankAccount->credit(new Payment(100, new Currency(currency: 'PLN')));
        $this->bankAccount->credit(new Payment(23.24, new Currency(currency: 'PLN')));

        $this->assertEquals(123.24, $this->bankAccount->getBalance());
    }

    public function testCreditCurrencyMissMatch(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bankAccount->credit(new Payment(100, new Currency('USD')));
    }

    public function testDebit(): void
    {
        $this->bankAccount->credit(new Payment(200, new Currency(currency: 'PLN')));
        $this->bankAccount->debit(new Payment(100, new Currency('PLN',)));

        $this->assertEquals(99.5, $this->bankAccount->getBalance());
    }

    public function testDebitDailyLimitExceeded(): void
    {
        $this->bankAccount->credit(new Payment(500, new Currency(currency: 'PLN')));
        $this->bankAccount->debit(new Payment(50, new Currency('PLN',)));
        $this->bankAccount->debit(new Payment(50, new Currency('PLN',)));
        $this->bankAccount->debit(new Payment(50, new Currency('PLN',)));
        
        $this->expectException(\InvalidArgumentException::class);
        $this->bankAccount->debit(new Payment(50, new Currency('PLN',)));
    }
}