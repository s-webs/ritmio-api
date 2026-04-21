<?php

namespace App\Services\Categories;

use App\Models\Category;
use App\Models\User;

class CategoryResolver
{
    public function resolve(User $user, string $type, ?string $slug): ?Category
    {
        $normalized = $this->normalize($slug);

        return Category::query()
            ->where('type', $type)
            ->where('slug', $normalized)
            ->where(fn ($q) => $q->where('is_system', true)->orWhere('user_id', $user->id))
            ->first();
    }

    public function normalize(?string $slug): string
    {
        $clean = strtolower((string) $slug);

        return preg_replace('/[^a-z0-9_]/', '', $clean) ?: 'other';
    }
}
