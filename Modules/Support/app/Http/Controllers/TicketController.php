<?php

namespace Modules\Support\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Support\Models\Ticket;
use Modules\Support\Models\TicketMessage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('messages')->orderBy('updated_at', 'desc')->paginate(10);
        return view('support::index', compact('tickets'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $ticket = Ticket::with('messages')->findOrFail($id);
        return view('support::show', compact('ticket'));
    }

    /**
     * Store a reply message.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth('admin')->id() ?? 1, // Assume Admin ID 1 if not auth
            'message' => $request->message,
        ]);

        // Update ticket timestamp
        $ticket->touch();

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Open,In Progress,Closed',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Ticket status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('support.tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
