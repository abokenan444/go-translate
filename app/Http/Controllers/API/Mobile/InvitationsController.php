<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\CallInvitation;
use App\Models\MobileContact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationsController extends Controller
{
    /**
     * Display a listing of invitations for the authenticated user.
     */
    public function index(Request $request)
    {
        $invitations = CallInvitation::where('to_user_id', Auth::id())
            ->with('fromUser:id,name,email')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($inv) => [
                'id' => $inv->id,
                'from_user' => [
                    'id' => $inv->fromUser->id,
                    'name' => $inv->fromUser->name,
                    'email' => $inv->fromUser->email,
                ],
                'status' => $inv->status,
                'created_at' => $inv->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'invitations' => $invitations,
        ]);
    }

    /**
     * Store a newly created invitation.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'contact_id' => 'required|integer|exists:contacts,id',
        ]);

        $contact = MobileContact::where('id', $data['contact_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$contact->contact_user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Contact is not a registered user',
            ], 400);
        }

        // Check if invitation already exists
        $existing = CallInvitation::where('from_user_id', Auth::id())
            ->where('to_user_id', $contact->contact_user_id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation already sent',
            ], 400);
        }

        $invitation = CallInvitation::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $contact->contact_user_id,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent',
            'invitation' => [
                'id' => $invitation->id,
                'to_user_id' => $invitation->to_user_id,
                'status' => $invitation->status,
            ],
        ], 201);
    }

    /**
     * Accept an invitation.
     */
    public function accept(Request $request, int $id)
    {
        $invitation = CallInvitation::where('id', $id)
            ->where('to_user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $invitation->update(['status' => 'accepted']);

        // Optionally create reverse contact if not exists
        $existingContact = MobileContact::where('user_id', Auth::id())
            ->where('contact_user_id', $invitation->from_user_id)
            ->first();

        if (!$existingContact) {
            $fromUser = User::find($invitation->from_user_id);
            MobileContact::create([
                'user_id' => Auth::id(),
                'contact_user_id' => $fromUser->id,
                'name' => $fromUser->name,
                'email' => $fromUser->email,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invitation accepted',
        ]);
    }

    /**
     * Reject an invitation.
     */
    public function reject(Request $request, int $id)
    {
        $invitation = CallInvitation::where('id', $id)
            ->where('to_user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $invitation->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Invitation rejected',
        ]);
    }

    /**
     * Remove the specified invitation.
     */
    public function destroy(int $id)
    {
        $invitation = CallInvitation::where('id', $id)
            ->where(function ($query) {
                $query->where('from_user_id', Auth::id())
                      ->orWhere('to_user_id', Auth::id());
            })
            ->firstOrFail();

        $invitation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invitation deleted',
        ]);
    }
}
