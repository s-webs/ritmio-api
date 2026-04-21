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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('raw_text')->nullable();
            $table->string('status')->default('pending');
            $table->string('priority')->default('normal');
            $table->date('due_date')->nullable();
            $table->time('due_time')->nullable();
            $table->boolean('is_ai_generated')->default(false);
            $table->boolean('needs_confirmation')->default(false);
            $table->decimal('ai_confidence', 5, 2)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
