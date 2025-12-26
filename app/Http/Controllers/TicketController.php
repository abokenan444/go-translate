<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of user's tickets
     */
    public function index()
    {
        $tickets = Complaint::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:technical,billing,feature_request,bug_report,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $user = Auth::user();
        
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $user->id, 'public');
                $attachmentPaths[] = $path;
            }
        }

        $ticket = Complaint::create([
            'user_id' => $user->id,
            'ticket_number' => 'TKT-' . strtoupper(Str::random(8)),
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'attachments' => $attachmentPaths,
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully! Ticket #' . $ticket->ticket_number);
    }

    /**
     * Display the specified ticket
     */
    public function show(Complaint $ticket)
    {
        // Ensure user can only view their own tickets
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this ticket');
        }

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Update the specified ticket (for adding replies)
     */
    public function update(Request $request, Complaint $ticket)
    {
        // Ensure user can only update their own tickets
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // Here you would typically add a reply to a ticket_replies table
        // For now, we'll just update the message
        $ticket->update([
            'message' => $ticket->message . "\n\n--- User Reply ---\n" . $validated['message'],
            'status' => 'waiting_response',
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Reply added successfully!');
    }

    /**
     * Close the ticket
     */
    public function close(Complaint $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket closed successfully!');
    }
}
