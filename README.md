# debitznloanz

Tests use SQLite

To start api:
# php artisan serve

To create tests:
# php artisan make:test Domains/PaymentMethods/DebitCard/Feature/ListDebitCardsTest --pest

Migrations:

# php artisan migrate --env=testing

To create user:

# php artisan tinker

# \App\Domains\User\Models\User::factory()->create();

Code standard checks:

# vendor/bin/phpcs --standard=PSR12 app/

# vendor/bin/phpstan analyse --memory-limit=512M

Code standard fixes:

# vendor/bin/phpcbf --standard=PSR12 app/


ToFix: 

uses(RefreshDatabase::class);
create a cast for vault details to encrypt.



