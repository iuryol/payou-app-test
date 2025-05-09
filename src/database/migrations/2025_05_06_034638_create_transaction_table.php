<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'transactions', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['deposit','transfer','reversal']);
                $table->decimal('amount');
                $table->foreignId('sender_id')->nullable();
                $table->foreignId('receiver_id')->nullable();
                $table->foreignId('reversed_transaction_id')->nullable();
                $table->enum('status', ['completed','reversed','failed','pending']);
                $table->text('description')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
