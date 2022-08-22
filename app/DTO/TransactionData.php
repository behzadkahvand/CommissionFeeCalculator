<?php

namespace App\DTO;

use App\Enums\OperationType;
use App\Enums\UserType;
use DateTimeInterface;

class TransactionData
{
    public function __construct(
        private DateTimeInterface $operationDate,
        private int $userId,
        private UserType $userType,
        private OperationType $operationType,
        private string $operationAmount,
        private string $operationCurrency
    ) {
    }

    public function getOperationDate(): DateTimeInterface
    {
        return $this->operationDate;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserType(): UserType
    {
        return $this->userType;
    }

    public function getOperationType(): OperationType
    {
        return $this->operationType;
    }

    public function getOperationAmount(): string
    {
        return $this->operationAmount;
    }

    public function getOperationCurrency(): string
    {
        return $this->operationCurrency;
    }
}
