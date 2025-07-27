<?php

namespace App\Providers;

use App\Domains\Loan\Services\LoanService;
use App\Domains\PaymentMethod\Contracts\PaymentMethodStrategy;
use App\Domains\PaymentMethod\DebitCard\Repositories\DebitCardRepository;
use App\Domains\PaymentMethod\DebitCard\Services\DebitCardService;
use App\Domains\PaymentMethod\DebitCard\Strategies\DebitCardStrategy;
use App\Domains\Transaction\Contracts\TransactionStrategy;
use App\Domains\Transaction\TransactionTypes\DebitCardTransactionType;
use App\Domains\Transaction\TransactionTypes\ScheduledPaymentTransactionType;
use FilesystemIterator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Payment Method Bindings
        $this->app->bind(
            PaymentMethodStrategy::class,
            DebitCardStrategy::class
        );
        // Repository Bindings just for injection
        $this->app->bind(
            DebitCardRepository::class,
            DebitCardRepository::class
        );
        // Service Bindings just for injection
        $this->app->bind(
            DebitCardService::class,
            DebitCardService::class
        );
        $this->app->bind(
            LoanService::class,
            LoanService::class
        );

//        $this->app->bind(
//            \App\Domains\Transaction\Services\TransactionService::class,
//            \App\Domains\Transaction\Services\TransactionService::class
//        );

//        $this->app->bind(\App\Domains\Transaction\TransactionTypes\DebitCardTransactionType::class);
//        $this->app->bind(\App\Domains\Transaction\TransactionTypes\ScheduledPaymentTransactionType::class);


        $this->app->bind(
            TransactionStrategy::class,
            DebitCardTransactionType::class
        );

        $this->app->bind(
            TransactionStrategy::class,
            ScheduledPaymentTransactionType::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $migrator = app(Migrator::class);
        $domainPath = app_path('Domains');

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($domainPath, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir() && $file->getFilename() === 'Migrations') {
                $migrator->path($file->getPathname());
            }
        }
    }
}
