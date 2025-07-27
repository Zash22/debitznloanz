<?php

namespace App\Domains\Transaction\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('debit_card_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debit_card_id')->constrained();
            $table->decimal('amount', 12, 2);
            $table->string('payment_reference');
            $table->foreignId('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('debit_card_transactions');
    }
};
