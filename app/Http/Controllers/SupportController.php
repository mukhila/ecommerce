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

            // Store ticket number in session so guest can view the success page
            if (!Auth::check()) {
                session(['last_created_ticket' => $ticket->ticket_number]);
            }

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

        // Verify ticket ownership
        if ($ticket->user_id) {
            // Ticket belongs to a registered user — require matching auth
            if (!Auth::check() || (int) $ticket->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access');
            }
        } else {
            // Guest ticket — only viewable in the same session that created it
            if (session('last_created_ticket') !== $ticket->ticket_number) {
                abort(403, 'Unauthorized access');
            }
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

        // Verify ticket ownership — user must own the ticket
        if ($ticket->user_id && (int) $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Guest tickets (no user_id) are not viewable via this route
        // since it requires auth middleware
        if (!$ticket->user_id) {
            abort(403, 'Unauthorized access');
        }

        return view('support.show', compact('ticket'));
    }
}
