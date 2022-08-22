<?php

namespace App\Services;

use App\DTO\TransactionData;
use App\Enums\OperationType;
use App\Enums\UserType;
use App\Exceptions\UnSupportedCalculationRuleException;
use App\Services\CalculatorFeeRules\CalculatorFeeRuleInterface;
use App\Services\Utils\CsvReaderService;

class CommissionFeeCalculatorService
{
    public function __construct(
        private CsvReaderService $csvReaderService,
        private iterable $rules
    ) {
    }

    public function calculateCommission(string $filePath, array $currencyRates): array
    {
        $commissionFees = [];

        foreach ($this->csvReaderService->read($filePath) as $line) {
            $ruleIsValid     = false;
            $transactionData = $this->makeTransactionData($line);

            /** @var CalculatorFeeRuleInterface $rule */
            foreach ($this->rules as $rule) {
                if ($rule->supports($transactionData)) {
                    $ruleIsValid      = true;
                    $commissionFees[] = $rule->calculate($transactionData, $currencyRates)->roundUp()->format();
                }
            }

            if (!$ruleIsValid) {
                throw new UnSupportedCalculationRuleException(
                    "There is not any calculation rule for given transaction!"
                );
            }
        }

        return $commissionFees;
    }

    protected function makeTransactionData(array $line): TransactionData
    {
        return new TransactionData(
            new \DateTime($line[0]), $line[1], UserType::from($line[2]),
            OperationType::from($line[3]), $line[4], $line[5]
        );
    }
}
