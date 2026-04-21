<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AI\AiCommandParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AiParseTextTest extends TestCase
{
    use RefreshDatabase;

    public function test_parse_text_creates_pending_expense_transaction(): void
    {
        $mock = Mockery::mock(AiCommandParser::class);
        $mock->shouldReceive('parse')->once()->andReturn([
            'intent' => 'create_expense',
            'language' => 'ru',
            'confidence' => 0.92,
            'expense' => [
                'amount' => 7000,
                'currency' => 'KZT',
                'category' => 'other',
                'date' => now()->toDateString(),
            ],
            'tasks' => [],
        ]);
        $this->app->instance(AiCommandParser::class, $mock);

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ai/parse-text', ['text' => 'Я потратил 7000']);

        $response->assertOk()->assertJsonPath('data.parsed.intent', 'create_expense');
        $this->assertDatabaseHas('transactions', ['user_id' => $user->id, 'type' => 'expense', 'needs_confirmation' => true]);
        $this->assertDatabaseHas('ai_interactions', ['user_id' => $user->id, 'intent' => 'create_expense']);
    }
}
