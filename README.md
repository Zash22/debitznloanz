# debitznloanz

Tests use SQLite

To start api:
# php artisan serve

To create tests:
# php artisan make:test Domains/PaymentMethods/DebitCard/Feature/ListDebitCardsTest --pest

Migrations:

# php artisan migrate --env=testing


ToFix: 

uses(RefreshDatabase::class);

