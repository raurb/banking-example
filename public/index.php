<?php

use Raurb\BankingExample\Domain\Model\Banking\BankAccount;
use Raurb\BankingExample\Domain\ValueObject\Banking\Currency;
use Raurb\BankingExample\Domain\ValueObject\Banking\Payment;

require_once __DIR__ . '/../vendor/autoload.php';

$bankAccount = BankAccount::create('PLN', 0.005);

$bankAccount->credit(new Payment(500, new Currency('PLN')));

$bankAccount->debit(new Payment(100, new Currency('PLN')));
$bankAccount->debit(new Payment(100, new Currency('PLN')));
$bankAccount->debit(new Payment(100, new Currency('PLN')));
$bankAccount->debit(new Payment(50, new Currency('PLN')));

echo 'Balans: ' . $bankAccount->getBalance();