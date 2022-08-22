<?php

namespace App\Services\CalculatorFeeRules;

use App\DTO\AmountData;
use App\DTO\TransactionData;
use App\Enums\OperationType;
use App\Enums\UserType;
use App\Services\CurrencyConverter;

class WithdrawClientPrivateRule implements CalculatorFeeRuleInterface
{
    public function __construct(private CurrencyConverter $currencyConverter)
    {
    }

    private const COMMISSION_RATE = 0.003;

    private const MAX_FREE_CHARGE = 1000;

    private const FREE_CHARGE_COUNT = 3;

    private const CURRENCY_RATE_BASE = "EUR";

    private static array $userWithdrawHistory = [];

    public function supports(TransactionData $transactionData): bool
    {
        return (OperationType::withdraw === $transactionData->getOperationType())
            && (UserType::private === $transactionData->getUserType());
    }

    public function calculate(TransactionData $transactionData, array $currencyRates): AmountData
    {
        $amount            = $transactionData->getOperationAmount();
        $currencyConverter = $this->currencyConverter->setRates($currencyRates);

        if ($this->shouldExchangeRate($transactionData)) {
            $amount = $currencyConverter->convert(
                $amount,
                $transactionData->getOperationCurrency(),
                self::CURRENCY_RATE_BASE
            );
        }

        $userWithdrawPerWeekKey = $transactionData->getUserId() . "-" . date(
                "o-W",
                $transactionData->getOperationDate()->getTimestamp()
            );

        $UserWeekWithdrawHistory = $this->getHistory($userWithdrawPerWeekKey);

        $remainedFreeCharge = $UserWeekWithdrawHistory['remainedFreeCharge'] - $amount;

        if ($this->isFreeOfTheCharge($remainedFreeCharge, $UserWeekWithdrawHistory['count'])) {
            $commissionFee = 0;
        } elseif ($this->isMoreThanAllowedCount($remainedFreeCharge, $UserWeekWithdrawHistory['count'])) {
            $commissionFee      = $amount * self::COMMISSION_RATE;
            $remainedFreeCharge = 0;
        } else {
            $commissionFee      = abs($remainedFreeCharge) * self::COMMISSION_RATE;
            $remainedFreeCharge = 0;
        }

        if ($commissionFee > 0 && $this->shouldExchangeRate($transactionData)) {
            $commissionFee = $currencyConverter->convert(
                $commissionFee,
                self::CURRENCY_RATE_BASE,
                $transactionData->getOperationCurrency()
            );
        }

        $this->setHistory(
            $userWithdrawPerWeekKey,
            $UserWeekWithdrawHistory['count'] + 1,
            $remainedFreeCharge
        );

        return new AmountData($commissionFee,findCurrencyPrecision($transactionData->getOperationAmount()));
    }

    private function getHistory(string $key): array
    {
        if (isset(static::$userWithdrawHistory[$key])) {
            $history = static::$userWithdrawHistory[$key];
        } else {
            $history = [
                "count"              => 1,
                "remainedFreeCharge" => self::MAX_FREE_CHARGE,
            ];
        }

        return $history;
    }

    private function setHistory(
        string $key,
        int $count,
        float $remainedFreeCharge
    ): void {
        static::$userWithdrawHistory[$key] = compact("count", 'remainedFreeCharge');
    }

    private function shouldExchangeRate(TransactionData $transactionData): bool
    {
        return self::CURRENCY_RATE_BASE !== $transactionData->getOperationCurrency();
    }

    private function isFreeOfTheCharge(float $remainedFreeCharge, int $count): bool
    {
        return $remainedFreeCharge >= 0 && $count <= self::FREE_CHARGE_COUNT;
    }

    private function isMoreThanAllowedCount(float|int $remainedFreeCharge, $count): bool
    {
        return $remainedFreeCharge >= 0 && $count <= self::FREE_CHARGE_COUNT;
    }
}
