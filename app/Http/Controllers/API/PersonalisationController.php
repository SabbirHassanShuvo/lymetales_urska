<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CartException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonalisationController extends Controller
{
    public function __construct(private CartManager $cart) {}

    /**
     * POST /api/shop/personalisation
     *
     * Accepts multipart/form-data so an optional preview image can be uploaded.
     *
     * Fields:
     *   product_id   int       required
     *   quantity     int       optional (default 1)
     *   child_name   string    required
     *   dedication   string    optional
     *   image        file      optional  — preview image, stored in public/storage/personalisations/
     *   fields[*]    string    optional  — dynamic personalisation key/value pairs
     *                                     e.g. fields[character_gender]=Girl
     *                                          fields[skin_tone]=#F5CBA7
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['sometimes', 'integer', 'min:1', 'max:99'],
            'child_name' => ['required', 'string', 'max:100'],
            'dedication' => ['sometimes', 'nullable', 'string', 'max:500'],
            'image'      => ['sometimes', 'nullable', 'image', 'max:5120'], // max 5 MB
            'fields'     => ['sometimes', 'nullable', 'array'],
        ]);

        // ── Handle preview image upload ────────────────────────────────────
        $imageUrl = null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageUrl = $this->storePreviewImage($request->file('image'));
        }

        // ── Build fields JSON ──────────────────────────────────────────────
        $fields = [];
        if ($request->filled('fields') && is_array($request->fields)) {
            $fields = $request->fields;
        }

        // ── Save to database ───────────────────────────────────────────────
        try {
            $personalisation = \App\Models\Personalisation::create([
                'product_id'    => $request->product_id,
                'quantity'      => $request->input('quantity', 1),
                'child_name'    => trim($request->child_name),
                'dedication'    => $request->dedication ? trim($request->dedication) : null,
                'preview_image' => $imageUrl,
                'fields'        => $fields,
            ]);
        } catch (\Exception $e) {
            // Clean up uploaded image if db save fails
            if ($imageUrl) {
                $this->deleteFile($imageUrl);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to save personalisation.',
                'error' => 'Database error: Table might not exist. Please run migrations.',
            ], 422);
        }

        return response()->json([
            'success'         => true,
            'message'         => 'Book personalisation saved successfully.',
            'personalisation' => $personalisation,
        ]);
    }

    // ── Private helpers ────────────────────────────────────────────────────

    /**
     * Store the uploaded preview image in public/storage/personalisations/
     * and return the public URL path (e.g. /storage/personalisations/prev_abc123.jpg).
     */
    private function storePreviewImage(\Illuminate\Http\UploadedFile $file): string
    {
        $dest = public_path('storage/personalisations');

        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        } 

        $filename = 'prev_' . uniqid('', true) . '_' . \Illuminate\Support\Str::random(6)
                    . '.' . $file->getClientOriginalExtension();

        $file->move($dest, $filename);

        return '/storage/personalisations/' . $filename;
    }

    /**
     * Delete a stored preview image by its path.
     */
    private function deleteFile(string $path): void
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $fullPath = public_path(ltrim($path, '/'));

        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
