<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\CommissionFeeCalculatorService;
use Tests\TestCase;

class CommissionFeeCalculatorServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCalculateCommissionSuccessfully()
    {
        $service = $this->app->make(CommissionFeeCalculatorService::class);

        $transactions = $service->calculateCommission(__DIR__ . '/../file.csv', [
            'EUR' => 1,
            'USD' => 1.1497,
            'JPY' => 129.53,
        ]);
        $this->assertEquals([
            "0.60",
            "3.00",
            "0.00",
            "0.06",
            "1.50",
            "0",
            "0.70",
            "0.31",
            "0.30",
            "3.00",
            "0.00",
            "0.00",
            "8612",
        ], $transactions);
    }
}
