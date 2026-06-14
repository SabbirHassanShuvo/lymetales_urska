<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    /**
     * Subscribe an email to the newsletter.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = strtolower(trim($request->input('email')));

        // Check if already subscribed
        $existing = Subscriber::where('email', $email)->first();
        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'You are already subscribed to our newsletter!',
            ], 200);
        }

        // Create new subscriber
        Subscriber::create([
            'email' => $email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing! Keep an eye on your inbox for magical updates.',
        ], 201);
    }
}
