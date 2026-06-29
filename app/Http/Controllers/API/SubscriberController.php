<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeSubscriptionMail;

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
        $lang  = strtolower($request->input('language_type', $request->input('language', 'en')));

        // Check if already subscribed
        $existing = Subscriber::where('email', $email)->first();
        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'You are already subscribed to our newsletter!',
            ], 200);
        }

        // Create new subscriber in Database
        Subscriber::create([
            'email' => $email,
            'language' => $lang
        ]);

        // Generate a unique 10% coupon code (e.g. WELCOME10-ABC123) valid for 1 use
        $couponCode = 'WELCOME10-' . strtoupper(Str::random(6));
        try {
            Coupon::create([
                'code'          => $couponCode,
                'type'          => 'percentage',
                'value'         => 10.00,
                'description'   => '10% Welcome Discount for Newsletter Subscription',
                'expiry_date'   => now()->addDays(30),
                'usage_limit'   => 1,
                'used_count'    => 0,
                'status'        => true,
                'language_type' => $lang,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Welcome coupon creation failed: ' . $e->getMessage());
            // Fallback to static code if unique creation fails
            $couponCode = 'WELCOME10';
        }

        // Integrate Mailerlite API
        try {
            $mailerliteKey = config('services.mailerlite.key');
            if ($mailerliteKey) {
                // Determine group ID based on language
                $groups = [
                    'en' => config('services.mailerlite.group_en'),
                    'si' => config('services.mailerlite.group_si'),
                    'sl' => config('services.mailerlite.group_si'),
                    'hr' => config('services.mailerlite.group_hr'),
                ];
                $groupId = $groups[$lang] ?? config('services.mailerlite.group_en');
                
                $payload = [
                    'email' => $email,
                    'status' => 'active'
                ];
                if ($groupId) {
                    $payload['groups'] = [$groupId];
                }

                \Illuminate\Support\Facades\Http::withToken($mailerliteKey)
                    ->post('https://connect.mailerlite.com/api/subscribers', $payload);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mailerlite Subscription Failed: ' . $e->getMessage());
        }

        // Send Welcome email with coupon code
        try {
            Mail::to($email)->send(new WelcomeSubscriptionMail($couponCode, $lang));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Welcome subscription email sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Thank you for subscribing! Keep an eye on your inbox for magical updates.',
            'coupon_code' => $couponCode,
        ], 201);
    }
}
