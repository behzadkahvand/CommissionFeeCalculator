<?php

namespace App\Services\CalculatorFeeRules;

use App\DTO\AmountData;
use App\DTO\TransactionData;
use App\Enums\OperationType;

class DepositRule implements CalculatorFeeRuleInterface
{
    private const COMMISSION_RATE = 0.0003;

    public function supports(TransactionData $transactionData): bool
    {
        return OperationType::deposit === $transactionData->getOperationType();
    }

    public function calculate(TransactionData $transactionData, array $currencyRates): AmountData
    {
        return new AmountData(
            floatval($transactionData->getOperationAmount()) * self::COMMISSION_RATE,
            findCurrencyPrecision($transactionData->getOperationAmount())
        );
    }
}
