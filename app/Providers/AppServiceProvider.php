<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Payment Method Bindings
        $this->app->bind(
            \App\Domains\PaymentMethod\Contracts\PaymentMethodStrategy::class,
            \App\Domains\PaymentMethod\DebitCard\Strategies\DebitCardStrategy::class
        );
        // Repository Bindings just for injection
        $this->app->bind(
            \App\Domains\PaymentMethod\DebitCard\Repositories\DebitCardRepository::class,
            \App\Domains\PaymentMethod\DebitCard\Repositories\DebitCardRepository::class
        );
        // Service Bindings just for injection
        $this->app->bind(
            \App\Domains\PaymentMethod\DebitCard\Services\DebitCardService::class,
            \App\Domains\PaymentMethod\DebitCard\Services\DebitCardService::class
        );
        $this->app->bind(
            \App\Domains\Loan\Services\LoanService::class,
            \App\Domains\Loan\Services\LoanService::class
        );
        $this->app->bind(
            \App\Domains\Transaction\Services\TransactionService::class,
            \App\Domains\Transaction\Services\TransactionService::class
        );

        $this->app->bind(
            \App\Domains\Transaction\Contracts\TransactionStrategy::class,
            \App\Domains\Transaction\TransactionTypes\DebitCardTransactionType::class
        );

        $this->app->bind(
            \App\Domains\Transaction\Contracts\TransactionStrategy::class,
            \App\Domains\Transaction\TransactionTypes\ScheduledPaymentTransactionType::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
