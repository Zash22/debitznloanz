<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loans', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('term');
            $table->enum('frequency', ['monthly', 'biweekly']);
            $table->decimal('term_amount', 12, 2);
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('remaining_balance', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('loans');
    }
};
