<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * GET /api/shop/categories
     *
     * Returns all active parent categories with their subcategories nested.
     */
    public function index()
    {
        $categories = Category::with(['subcategories' => function ($q) {
                $q->where('status', true)->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories->map(fn ($c) => $this->formatCategory($c, withChildren: true)),
        ]);
    }

    /**
     * GET /api/shop/categories/{slug}
     *
     * Returns a single category (parent or sub) with its subcategories.
     */
    public function show(string $slug)
    {
        $category = Category::with(['subcategories' => function ($q) {
                $q->where('status', true)->orderBy('name');
            }, 'parent:id,name,slug'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $this->formatCategory($category, withChildren: true, withParent: true),
        ]);
    }

    // ── Private helpers ────────────────────────────────────────────────────

    private function formatCategory(Category $c, bool $withChildren = false, bool $withParent = false): array
    {
        $data = [
            'id'          => $c->id,
            'name'        => $c->name,
            'slug'        => $c->slug,
            'description' => $c->description,
            'is_special'  => $c->is_special,
            'parent_id'   => $c->parent_id,
        ];

        if ($withParent && $c->parent) {
            $data['parent'] = [
                'id'   => $c->parent->id,
                'name' => $c->parent->name,
                'slug' => $c->parent->slug,
            ];
        }

        if ($withChildren) {
            $data['subcategories'] = $c->subcategories
                ->map(fn ($sub) => [
                    'id'          => $sub->id,
                    'name'        => $sub->name,
                    'slug'        => $sub->slug,
                    'description' => $sub->description,
                    'is_special'  => $sub->is_special,
                    'parent_id'   => $sub->parent_id,
                ])
                ->values();
        }

        return $data;
    }
}
