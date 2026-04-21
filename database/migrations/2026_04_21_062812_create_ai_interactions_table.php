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
        Schema::create('ai_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_file_id')->nullable()->constrained()->nullOnDelete();
            $table->string('intent');
            $table->string('language', 5)->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->text('raw_text')->nullable();
            $table->string('model')->nullable();
            $table->json('prompt_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->json('validation_errors')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'intent']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_interactions');
    }
};
