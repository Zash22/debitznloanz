# debitznloanz

To start api:
# php artisan serve

To run tests:
# php artisan test
# php artisan test --filter=CreateDebitCardTest

To create tests:
# php artisan make:test Feature/ListDebitCardsTest --pest


Migrations:
# php artisan migrate --env=testing

To create user:
# php artisan tinker
# \App\Domains\User\Models\User::factory()->create();

Code standard checks:
# vendor/bin/phpcs --standard=PSR12 app/
# vendor/bin/phpstan analyse --memory-limit=512M
# vendor/bin/phpmd app text cleancode,codesize,unusedcode,naming,controversial,design

Code standard fixes:
# vendor/bin/phpcbf --standard=PSR12 app/

To assign personal access token:
# php artisan tinker
# $user = User::find(1); 
# $token = $user->createToken('token-name');




ToFix: 

uses(RefreshDatabase::class);
create a cast for vault details to encrypt.
create repository for vault
generate new app key for test env
policy for viewAll DebitCard
Illuminate\Routing\Middleware\SubstituteBindings
php artisan test --env=testing    # Uses file mode (testing.sqlite)
php artisan test                  # Uses in-memory (phpunit.xml :memory)







