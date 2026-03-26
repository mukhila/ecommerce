<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Notifications\TicketAdminReplied;
use Modules\Admin\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SupportController extends Controller
{
    /**
     * Display listing of all support tickets with filters
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedAdmin']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search by ticket number, subject, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter by assigned admin
        if ($request->filled('assigned_to')) {
            if ($request->assigned_to == 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $tickets = $query->paginate(20);

        // Get all admins for assignment dropdown
        $admins = Admin::where('status', 1)->get(['id', 'name']);

        // Get statistics for dashboard cards (single query)
        $statusCounts = SupportTicket::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $stats = [
            'total' => SupportTicket::count(),
            'open' => $statusCounts->get('open', 0),
            'in_progress' => $statusCounts->get('in_progress', 0),
            'resolved' => $statusCounts->get('resolved', 0),
            'closed' => $statusCounts->get('closed', 0),
            'high_priority' => SupportTicket::where('priority', 'high')->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        return view('admin::support.index', compact('tickets', 'admins', 'stats'));
    }

    /**
     * Show ticket details with replies
     */
    public function show($ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)
                               ->with(['user', 'assignedAdmin', 'replies.user'])
                               ->firstOrFail();

        // Get all admins for assignment dropdown
        $admins = Admin::where('status', 1)->get(['id', 'name']);

        return view('admin::support.show', compact('ticket', 'admins'));
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $oldStatus = $ticket->status;
        $ticket->update(['status' => $request->status]);

        Log::info('Ticket status updated', [
            'ticket_number' => $ticket->ticket_number,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'updated_by' => auth('admin')->user()->name
        ]);

        return redirect()->back()->with('success', 'Ticket status updated successfully');
    }

    /**
     * Assign ticket to admin
     */
    public function assign(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'assigned_to' => 'nullable|exists:admins,id'
        ]);

        $oldAssignee = $ticket->assigned_to;
        $ticket->update(['assigned_to' => $request->assigned_to]);

        // If ticket was unassigned and now assigned, set status to in_progress
        if (!$oldAssignee && $request->assigned_to && $ticket->status == 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        Log::info('Ticket assigned', [
            'ticket_number' => $ticket->ticket_number,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => auth('admin')->user()->name
        ]);

        return redirect()->back()->with('success', 'Ticket assigned successfully');
    }

    /**
     * Add admin reply to ticket
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string|min:10'
        ]);

        $reply = SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => null, // admin replies use is_admin flag; no user FK
            'message' => $request->message,
            'is_admin' => true
        ]);

        // Update ticket status to in_progress if it's still open
        if ($ticket->status == 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        // Notify the customer via email
        try {
            Notification::route('mail', $ticket->email)
                ->notify(new TicketAdminReplied($ticket->fresh(), $reply));
        } catch (\Exception $e) {
            Log::warning('Failed to send admin reply notification: ' . $e->getMessage());
        }

        Log::info('Admin reply added to ticket', [
            'ticket_number' => $ticket->ticket_number,
            'replied_by' => auth('admin')->user()->name
        ]);

        return redirect()->back()->with('success', 'Reply sent and customer notified by email.');
    }

    /**
     * Bulk update tickets
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:support_tickets,id',
            'action' => 'required|in:assign,status,delete'
        ]);

        $tickets = SupportTicket::whereIn('id', $request->ticket_ids);

        switch ($request->action) {
            case 'assign':
                $request->validate(['assigned_to' => 'required|exists:admins,id']);
                $tickets->update(['assigned_to' => $request->assigned_to]);
                $message = 'Tickets assigned successfully';
                break;

            case 'status':
                $request->validate(['status' => 'required|in:open,in_progress,resolved,closed']);
                $tickets->update(['status' => $request->status]);
                $message = 'Ticket status updated successfully';
                break;

            case 'delete':
                $tickets->delete();
                $message = 'Tickets deleted successfully';
                break;
        }

        Log::info('Bulk ticket update', [
            'action' => $request->action,
            'ticket_count' => count($request->ticket_ids),
            'updated_by' => auth('admin')->user()->name
        ]);

        return redirect()->back()->with('success', $message);
    }
}
