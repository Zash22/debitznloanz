<?php

namespace App\Domains\Transaction\Factories;

use App\Domains\Transaction\Contracts\TransactionStrategy;
use App\Domains\Transaction\Services\TransactionService;
use App\Domains\Transaction\TransactionTypes\DebitCardTransactionType;
use App\Domains\Transaction\TransactionTypes\ScheduledPaymentTransactionType;
use InvalidArgumentException;
use Illuminate\Support\Facades\Cache;

class TransactionTypeFactory
{
    private array $typeToClass = [];

    public function __construct()
    {
        // Cache only the mapping, not the instances
        $this->typeToClass = Cache::rememberForever('transaction_type_classes', function () {
            $map = [];
            $dir = app_path('Domains/Transaction/TransactionTypes');
            foreach (glob($dir . '/*.php') as $file) {
                $class = 'App\\Domains\\Transaction\\TransactionTypes\\' . basename($file, '.php');
                if (class_exists($class)) {
                    // Use reflection to get the type string without instantiating
                    $ref = new \ReflectionClass($class);
                    if ($ref->hasMethod('getType')) {
                        $type = $ref->getMethod('getType')->invoke($ref->newInstanceWithoutConstructor());
                        $map[$type] = $class;
                    }
                }
            }
            return $map;
        });
    }

    public function make(string $type, array $data = [], bool $validate = true): TransactionStrategy
    {
        $type = strtolower($type);
        if (!isset($this->typeToClass[$type])) {
            throw new \InvalidArgumentException("Unknown transaction type: {$type}");
        }
        // Let the container resolve dependencies (including TransactionService)
        $strategy = app()->make($this->typeToClass[$type]);
        if ($validate) {
//            $strategy->validateTransaction($data);
        }
        return $strategy;
    }
}
