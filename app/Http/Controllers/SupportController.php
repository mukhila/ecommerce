<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupportController extends Controller
{
    /**
     * Show ticket submission form
     */
    public function create()
    {
        $userOrders = [];
        if (Auth::check()) {
            $userOrders = Order::where('user_id', Auth::id())
                             ->orderBy('created_at', 'desc')
                             ->get(['id', 'order_number']);
        }

        return view('support.create', compact('userOrders'));
    }

    /**
     * Store a new support ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'order_id' => 'nullable|string|max:255',
            'category' => 'required|in:General,Order Issue,Payment,Product,Returns,Shipping,Other',
            'priority' => 'required|in:low,medium,high',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        DB::beginTransaction();

        try {
            // Handle file upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('support_tickets', 'public');
            }

            // Create ticket
            $ticket = SupportTicket::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'order_id' => $validated['order_id'] ?? null,
                'category' => $validated['category'],
                'priority' => $validated['priority'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'attachment' => $attachmentPath,
                'status' => 'open'
            ]);

            DB::commit();

            return redirect()->route('support.success', $ticket->ticket_number)
                           ->with('success', 'Support ticket created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating support ticket: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Unable to create support ticket. Please try again.');
        }
    }

    /**
     * Show ticket success page
     */
    public function success($ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)->firstOrFail();

        // Ensure user can only view their own tickets (or anyone if not logged in)
        if (Auth::check() && $ticket->user_id && $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('support.success', compact('ticket'));
    }

    /**
     * Show user's tickets
     */
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    /**
     * Show ticket details
     */
    public function show($ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)
                               ->with(['replies.user'])
                               ->firstOrFail();

        // Ensure user can only view their own tickets
        if ($ticket->user_id && $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('support.show', compact('ticket'));
    }
}
