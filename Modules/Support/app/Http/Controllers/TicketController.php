<?php

namespace Modules\Support\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = SupportTicket::orderBy('updated_at', 'desc')->paginate(10);
        return view('support::index', compact('tickets'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with('replies.user')->findOrFail($id);
        return view('support::show', compact('ticket'));
    }

    /**
     * Store a reply message.
     */
    public function reply(Request $request, $id)
    {
        // Require authentication — either admin or regular user
        $adminId = Auth::guard('admin')->id();
        $userId = Auth::id();

        if (!$adminId && !$userId) {
            abort(401, 'Authentication required.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $adminId ?? $userId,
            'message' => $request->message,
            'is_admin' => (bool) $adminId,
        ]);

        // Update ticket timestamp
        $ticket->touch();
        
        // If ticket was closed/resolved, maybe re-open it? 
        // For now, let's keep status as is unless explicitly changed, or set to in_progress
        if($ticket->status === 'open') {
             $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Ticket status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.support.tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
