<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_summary_is_calculated_from_database_totals(): void
    {
        $user = User::factory()->create();
        $category = Category::query()->create([
            'type' => 'expense',
            'slug' => 'food',
            'name_ru' => 'Еда',
            'name_en' => 'Food',
            'is_system' => true,
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 1000,
            'currency' => 'KZT',
            'transaction_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/finance/summary/weekly');
        $response->assertOk()->assertJsonPath('expense_total', 1000);
    }
}
