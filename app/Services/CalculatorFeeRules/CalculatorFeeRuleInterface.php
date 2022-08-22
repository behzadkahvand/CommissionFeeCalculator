<?php

namespace App\Services\CalculatorFeeRules;

use App\DTO\AmountData;
use App\DTO\TransactionData;

interface CalculatorFeeRuleInterface
{
    public function calculate(TransactionData $transactionData, array $currencyRates): AmountData;

    public function supports(TransactionData $transactionData): bool;
}
