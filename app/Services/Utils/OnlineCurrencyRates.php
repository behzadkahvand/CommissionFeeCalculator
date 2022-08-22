<?php

namespace App\Services\Utils;

class OnlineCurrencyRates
{
    private const API_URL = "https://developers.paysera.com/tasks/api/currency-exchange-rates";

    private array $rates = [];

    public function __construct()
    {
        $this->fetchRates();
    }

    private function fetchRates(): void
    {
        $apiResult = (new HttpRequestService(self::API_URL))->sendGetRequest();
        if (!$apiResult->isSuccess()) {
            throw new \RuntimeException("There is a problem to connect server for getting currency rates!");
        }

        $this->rates = $apiResult->getContent()['rates'];
    }

    public function getRates(): array
    {
        return $this->rates;
    }
}
