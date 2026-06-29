<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GDPRController extends Controller
{
    public function index()
    {
        return view('admin.gdpr.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $exportData = [];

        // 1. Orders
        $orders = Order::where('email', $email)->get();
        if ($orders->isNotEmpty()) {
            $exportData['orders'] = $orders->toArray();
        }

        // 2. Contact Messages
        $messages = ContactMessage::where('email', $email)->get();
        if ($messages->isNotEmpty()) {
            $exportData['contact_messages'] = $messages->toArray();
        }

        // 3. Subscribers
        $subscriber = Subscriber::where('email', $email)->first();
        if ($subscriber) {
            $exportData['newsletter_subscription'] = $subscriber->toArray();
        }

        if (empty($exportData)) {
            return back()->with('error', 'No data found for the provided email address.');
        }

        $json = json_encode($exportData, JSON_PRETTY_PRINT);
        $filename = 'gdpr_export_' . Str::slug($email) . '_' . date('Ymd_His') . '.json';

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function anonymize(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $count = 0;

        // 1. Orders
        $orders = Order::where('email', $email)->get();
        foreach ($orders as $order) {
            $order->update([
                'email' => 'anonymized_' . Str::random(8) . '@example.com',
                'full_name' => '[ANONYMIZED]',
                'address' => '[ANONYMIZED]',
                'city' => '[ANONYMIZED]',
                'postal_code' => '[ANONYMIZED]',
                'phone' => '[ANONYMIZED]',
            ]);
            $count++;
        }

        // 2. Contact Messages
        $messages = ContactMessage::where('email', $email)->get();
        foreach ($messages as $message) {
            $message->update([
                'email' => 'anonymized_' . Str::random(8) . '@example.com',
                'name' => '[ANONYMIZED]',
                'phone' => '[ANONYMIZED]',
                'message' => '[ANONYMIZED CONTENT]'
            ]);
            $count++;
        }

        // 3. Subscribers
        $subscriber = Subscriber::where('email', $email)->first();
        if ($subscriber) {
            $subscriber->delete();
            $count++;
        }

        if ($count === 0) {
            return back()->with('error', 'No data found for the provided email address to anonymize.');
        }

        return back()->with('success', "Successfully anonymized {$count} records associated with {$email}.");
    }
}
