<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DataDeletionController extends Controller
{
    public function index()
    {
        return view('pages.data-deletion');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'reason'  => 'nullable|string|max:1000',
            'confirm' => 'accepted',
        ], [
            'confirm.accepted' => 'You must confirm that you understand this action is permanent.',
        ]);

        // Log the deletion request
        Log::info('Data deletion request received', [
            'name'   => $request->name,
            'email'  => $request->email,
            'reason' => $request->reason,
            'ip'     => $request->ip(),
        ]);

        // Notify admin by email
        try {
            Mail::raw(
                "Data Deletion Request\n\nName: {$request->name}\nEmail: {$request->email}\nReason: {$request->reason}\nIP: {$request->ip()}\nDate: " . now(),
                function ($message) use ($request) {
                    $message->to(config('mail.from.address'))
                            ->subject('Data Deletion Request - ' . $request->email);
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to send data deletion notification email: ' . $e->getMessage());
        }

        return redirect()->route('data-deletion')
            ->with('success', 'Your data deletion request has been received. We will process it within 30 days and send a confirmation to ' . $request->email . '.');
    }
}
