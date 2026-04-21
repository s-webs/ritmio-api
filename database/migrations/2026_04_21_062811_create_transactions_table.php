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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('KZT');
            $table->date('transaction_date');
            $table->string('merchant')->nullable();
            $table->string('source')->nullable();
            $table->text('description')->nullable();
            $table->text('raw_text')->nullable();
            $table->boolean('is_ai_generated')->default(false);
            $table->boolean('needs_confirmation')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'transaction_date']);
            $table->index(['user_id', 'needs_confirmation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
