<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display all approved reviews across all products
     * GET /api/shop/reviews
     */
    public function allReviews()
    {
        $reviews = ProductReview::with('product:id,title,slug')
            ->where('is_approved', true)
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $reviews->map(fn ($r) => [
                'id' => $r->id,
                'reviewer_name' => $r->reviewer_name,
                'title' => $r->title,
                'reviewer_location' => $r->reviewer_location,
                'rating' => (float) $r->rating,
                'comment' => $r->comment,
                'created_at' => $r->created_at->format('M d, Y'),
                'product' => $r->product ? [
                    'id' => $r->product->id,
                    'title' => $r->product->title,
                    'slug' => $r->product->slug,
                ] : null,
            ]),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * Display all approved reviews for a product
     * GET /api/shop/products/{id}/reviews
     */
    public function index(string $id)
    {
        // Find the product
        $product = Product::where('id', $id)
            ->where('status', true)
            ->firstOrFail();

        // Fetch approved reviews with pagination
        $reviews = ProductReview::where('product_id', $product->id)
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'product' => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'average_rating' => (float) $product->rating,
                    'total_reviews' => $product->reviews_count,
                ],
                'reviews' => $reviews->map(fn ($r) => [
                    'id' => $r->id,
                    'reviewer_name' => $r->reviewer_name,
                    'title' => $r->title,
                    'reviewer_location' => $r->reviewer_location,
                    'rating' => (float) $r->rating,
                    'comment' => $r->comment,
                    'created_at' => $r->created_at->format('M d, Y'),
                ]),
            ],
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * Submit a new review (Guest user)
     * POST /api/shop/products/{id}/reviews
     */
    public function store(Request $request, string $id)
    {
        // Find the product
        $product = Product::where('id', $id)
            ->where('status', true)
            ->firstOrFail();

        // Validation rules
        $validator = Validator::make($request->all(), [
            'reviewer_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'reviewer_email' => 'required|email|max:255',
            'reviewer_location' => 'nullable|string|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            // Custom validation messages
            'reviewer_name.required' => 'Please enter your name.',
            'reviewer_email.required' => 'Please enter your email.',
            'reviewer_email.email' => 'Please enter a valid email address.',
            'rating.required' => 'Please provide a rating.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating cannot be greater than 5.',
            'comment.required' => 'Please write a comment.',
            'comment.min' => 'Comment must be at least 10 characters long.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if the user already reviewed this product
        $exists = ProductReview::where('product_id', $product->id)
            ->where('reviewer_email', $request->reviewer_email)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a review for this product.',
            ], 409);
        }

        // Save review
        $review = ProductReview::create([
            'product_id' => $product->id,
            'reviewer_name' => $request->reviewer_name,
            'title' => $request->title,
            'reviewer_email' => $request->reviewer_email,
            'reviewer_location' => $request->reviewer_location,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true, // Set to true automatically for now so you can see it in API (Usually it should be false and await Admin approval)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your review has been submitted successfully!',
            'data' => [
                'id' => $review->id,
                'reviewer_name' => $review->reviewer_name,
                'rating' => (float) $review->rating,
            ],
        ], 201);
    }
}