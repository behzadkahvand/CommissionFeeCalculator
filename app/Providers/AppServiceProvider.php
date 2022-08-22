<?php

namespace App\Providers;

use App\Services\CalculatorFeeRules\DepositRule;
use App\Services\CalculatorFeeRules\WithdrawClientBusinessRule;
use App\Services\CalculatorFeeRules\WithdrawClientPrivateRule;
use App\Services\CommissionFeeCalculatorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->tag(
            [
                DepositRule::class,
                WithdrawClientBusinessRule::class,
                WithdrawClientPrivateRule::class,
            ],
            'commission.fee.rules'
        );
        $this->app->when(CommissionFeeCalculatorService::class)
                  ->needs('$rules')
                  ->giveTagged('commission.fee.rules');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
