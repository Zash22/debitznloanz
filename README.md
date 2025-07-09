# 💳 Debitznloanz

A domain-driven Laravel application for managing debit cards, loans, and scheduled payments.

---

## 🚀 Getting Started

### Serve the API
```bash
composer install
php artisan migrate --env=testing
php artisan serve
````

---

## 🧪 Testing

### Use SQLite for Tests

**Run a specific test:**

```bash
php artisan test --filter=CreateDebitCardTest
```

**Generate a new test (using Pest):**

```bash
php artisan make:test Domains/PaymentMethods/DebitCard/Feature/ListDebitCardsTest --pest
```

**Run migrations for testing:**

```bash
php artisan migrate --env=testing
```

---

## 👤 Bearer Token Generation

```bash
php artisan tinker
```
```php
$user- = \App\Domains\User\Models\User::factory()->create();
return $user->createToken('token_name')->plainTextToken;
```

---

## 📚 API Documentation

**Generate OpenAPI docs with Scribe:**

```bash
php artisan scribe:generate
```

**View Documentation:**

* URL: `/docs`

---

## 🧹 Code Quality

### Code Standards

**Check code style:**

```bash
vendor/bin/phpcs --standard=PSR12 app/
vendor/bin/phpcs --standard=PSR2 app/
```

**Fix code style automatically:**

```bash
vendor/bin/phpcbf --standard=PSR12 app/
vendor/bin/phpcbf --standard=PSR2 app/
```

### Static Analysis & Linting

```bash
vendor/bin/phpstan analyse --memory-limit=512M
vendor/bin/phpmd app text cleancode,codesize,unusedcode,naming,controversial,design
```

---

## 🛠️ To Fix / Improve

* [ ] Use `RefreshDatabase::class` in test setup
* [ ] Create a mutator for vault details encryption
* [ ] Create a repository for vault access
  *In real-world apps, consider services like AWS KMS or HashiCorp Vault*
* [ ] Add policy for `viewAll` DebitCard action
* [ ] Use UUIDs for card and user in URLs and payloads
* [ ] Move migrations and factories into their respective Domain folders
* [ ] Define status flow for loans and scheduled runs
* [ ] Implement autoloading for domain-based structure
* [ ] Improve date handling
* [ ] Add input validation to `LoanService`
* [ ] Configure Scribe properly for accurate documentation
* [ ] Extract scheduled payment logic into its own service
* [ ] Extract payment calculation logic into its own service
* [ ] Correct data types eg . `amount` should be `decimal`
* [ ] Use standard naming eg 'ref' abd 'reference'
* [ ] Apply code style formatter to all files
* [ ] Add more tests, aim for 100% coverage
* [ ] Add more tests to test for negative scenarios

---
