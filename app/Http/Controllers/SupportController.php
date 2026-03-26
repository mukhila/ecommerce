<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\Order;
use App\Notifications\TicketCreated;
use App\Notifications\TicketCustomerReplied;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|regex:/^[0-9+\-\s()]{7,20}$/',
            'order_id'   => 'nullable|string|max:255',
            'category'   => 'required|in:General,Order Issue,Payment,Product,Returns,Shipping,Other',
            'priority'   => 'required|in:low,medium,high',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('support_tickets', 'public');
            }

            $ticket = SupportTicket::create([
                'user_id'    => Auth::id(),
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'phone'      => $validated['phone'] ?? null,
                'order_id'   => $validated['order_id'] ?? null,
                'category'   => $validated['category'],
                'priority'   => $validated['priority'],
                'subject'    => $validated['subject'],
                'message'    => $validated['message'],
                'attachment' => $attachmentPath,
                'status'     => 'open',
            ]);

            DB::commit();

            // Store in session so guest can view the success page
            if (!Auth::check()) {
                session(['last_created_ticket' => $ticket->ticket_number]);
            }

            // Send confirmation email to customer
            try {
                Notification::route('mail', $ticket->email)
                    ->notify(new TicketCreated($ticket));
            } catch (\Exception $e) {
                Log::warning('Failed to send ticket confirmation email: ' . $e->getMessage());
            }

            return redirect()->route('support.success', $ticket->ticket_number)
                           ->with('success', 'Support ticket created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating support ticket: ' . $e->getMessage());
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

        if ($ticket->user_id) {
            if (!Auth::check() || (int) $ticket->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access');
            }
        } else {
            if (session('last_created_ticket') !== $ticket->ticket_number) {
                abort(403, 'Unauthorized access');
            }
        }

        return view('support.success', compact('ticket'));
    }

    /**
     * Show user's tickets list
     */
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
                                ->orderBy('updated_at', 'desc')
                                ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    /**
     * Show ticket details with conversation
     */
    public function show($ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)
                               ->with(['replies.user'])
                               ->firstOrFail();

        if (!$ticket->user_id || (int) $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('support.show', compact('ticket'));
    }

    /**
     * Customer follow-up reply on an existing ticket
     */
    public function reply(Request $request, $ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)->firstOrFail();

        if (!$ticket->user_id || (int) $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        if (in_array($ticket->status, ['resolved', 'closed'])) {
            return redirect()->back()->with('error', 'This ticket is closed. Please open a new ticket if you need further assistance.');
        }

        $request->validate([
            'message' => 'required|string|min:5',
        ]);

        $reply = SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $request->message,
            'is_admin'  => false,
        ]);

        // Re-open to in_progress if it was resolved/previously closed
        if ($ticket->status === 'open') {
            // Already open — no change needed
        } else {
            $ticket->update(['status' => 'in_progress']);
        }

        // Notify the assigned admin
        try {
            if ($ticket->assigned_to) {
                $admin = \Modules\Admin\Models\Admin::find($ticket->assigned_to);
                if ($admin) {
                    $admin->notify(new TicketCustomerReplied($ticket, $reply));
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to notify admin of customer reply: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Your reply has been sent.');
    }
}
