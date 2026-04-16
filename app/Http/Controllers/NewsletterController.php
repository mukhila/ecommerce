<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    /**
     * Subscribe an email address.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber) {
            if ($subscriber->is_active) {
                return $this->respond($request, 'You are already subscribed to our newsletter!', 'info');
            }
            // Re-subscribe
            $subscriber->update(['is_active' => true, 'unsubscribed_at' => null]);
            return $this->respond($request, 'Welcome back! You have been re-subscribed.', 'success');
        }

        NewsletterSubscriber::create(['email' => $request->email]);

        return $this->respond($request, 'Thank you for subscribing to our newsletter!', 'success');
    }

    /**
     * Unsubscribe via token link (email = plain address for simplicity).
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber && $subscriber->is_active) {
            $subscriber->update(['is_active' => false, 'unsubscribed_at' => now()]);
        }

        return $this->respond($request, 'You have been unsubscribed successfully.', 'success');
    }

    private function respond(Request $request, string $message, string $type)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'type' => $type]);
        }
        return redirect()->back()->with($type, $message);
    }
}
