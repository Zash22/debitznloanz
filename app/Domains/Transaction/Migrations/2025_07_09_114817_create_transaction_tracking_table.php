<?php

namespace App\Domains\Transaction\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable();
            $table->string('transaction_reference');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_tracking');
    }
};
