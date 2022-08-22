<?php

namespace App\Services;

use App\Exceptions\UnSupportedCurrencyException;

class CurrencyConverter
{
    private array $rates = [];

    public function setRates(array $rates): self
    {
        $this->rates = $rates;

        return $this;
    }

    public function convert(float|int $amount, string $fromCurrency, string $toCurrency): float
    {
        if (!isset($this->rates[$fromCurrency])) {
            throw new UnSupportedCurrencyException("Currency $fromCurrency is not supported!");
        }
        if (!isset($this->rates[$toCurrency])) {
            throw new UnSupportedCurrencyException("Currency $toCurrency is not supported!");
        }

        return $amount * ($this->rates[$toCurrency] / $this->rates[$fromCurrency]);
    }
}
