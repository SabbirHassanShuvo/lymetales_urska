<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SiteCategory;
use Illuminate\Http\Request;

class SiteCategoryController extends Controller
{
    /**
     * GET /api/shop/site-categories
     *
     * Returns all active site categories with their active site subcategories nested.
     */
    public function index()
    {
        $categories = SiteCategory::where('status', true)->orderBy('name')->get();
        $subcategories = \App\Models\SiteSubcategory::where('status', true)->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'subcategories' => $subcategories->unique('name')->values()->map(fn ($sub) => [
                    'id'          => $sub->id,
                    'name'        => $sub->name,
                    'description' => $sub->description,
                ])->values(),
                'categories' => $categories->map(fn ($c) => [
                    'id'          => $c->id,
                    'name'        => $c->name,
                    'description' => $c->description,
                    'is_special'  => (bool) $c->is_special,
                    'slug'        => $c->slug,
                ])->values(),
            ]
        ]);
    }

    /**
     * GET /api/shop/site-categories/{id}
     *
     * Returns a single site category with its active site subcategories.
     */
    public function show(string $id)
    {
        $category = SiteCategory::with(['subcategories' => function ($q) {
                $q->active();
            }])
            ->where('id', $id)
            ->where('status', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $this->formatCategory($category, withChildren: true),
        ]);
    }

    // ── Private helpers ────────────────────────────────────────────────────

    private function formatCategory(SiteCategory $c, bool $withChildren = false): array
    {
        $data = [
            'id'          => $c->id,
            'name'        => $c->name,
            'slug'        => $c->slug,
            'description' => $c->description,
            'is_special'  => $c->is_special,
        ];

        if ($withChildren) {
            $data['subcategories'] = $c->subcategories
                ->map(fn ($sub) => [
                    'id'          => $sub->id,
                    'name'        => $sub->name,
                    'description' => $sub->description,
                ])
                ->values();
        }

        return $data;
    }
}
