<?php

namespace App\Services\CalculatorFeeRules;

use App\DTO\AmountData;
use App\DTO\TransactionData;
use App\Enums\OperationType;
use App\Enums\UserType;

class WithdrawClientBusinessRule implements CalculatorFeeRuleInterface
{
    private const COMMISSION_RATE = 0.005;

    public function supports(TransactionData $transactionData): bool
    {
        return (OperationType::withdraw === $transactionData->getOperationType())
            && (UserType::business === $transactionData->getUserType());
    }

    public function calculate(TransactionData $transactionData, array $currencyRates): AmountData
    {
        return new AmountData(
            floatval($transactionData->getOperationAmount()) * self::COMMISSION_RATE,
            findCurrencyPrecision($transactionData->getOperationAmount())
        );
    }
}
