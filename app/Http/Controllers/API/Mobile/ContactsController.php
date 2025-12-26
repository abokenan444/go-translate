<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\MobileContact;
use App\Models\MobileNotification;
use App\Models\User;
use App\Notifications\ContactAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ContactsController extends Controller
{
    public function index(Request $request)
    {
        $contacts = MobileContact::where('user_id', Auth::id())
            ->with('contactUser:id,name,email')
            ->orderByDesc('is_favorite')
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'phone' => $c->phone,
                'email' => $c->email,
                'avatar_url' => $c->avatar_url,
                'is_favorite' => $c->is_favorite,
                'is_registered' => $c->contact_user_id !== null,
                'contact_user_id' => $c->contact_user_id,
                'last_called_at' => $c->last_called_at?->toIso8601String(),
            ]);

        return response()->json(['success' => true, 'contacts' => $contacts]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        // Check if contact email matches a registered user
        $contactUserId = null;
        $contactUser = null;
        if (!empty($data['email'])) {
            $contactUser = User::where('email', $data['email'])->first();
            if ($contactUser && $contactUser->id !== Auth::id()) {
                $contactUserId = $contactUser->id;
            }
        }

        $contact = MobileContact::create([
            'user_id' => Auth::id(),
            'contact_user_id' => $contactUserId,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
        ]);

        // Send notification to the added contact
        $this->sendContactAddedNotification($contactUser, $data['email'] ?? null);

        return response()->json([
            'success' => true,
            'contact' => [
                'id' => $contact->id,
                'name' => $contact->name,
                'phone' => $contact->phone,
                'email' => $contact->email,
                'is_favorite' => $contact->is_favorite,
                'is_registered' => $contact->contact_user_id !== null,
            ],
            'notification_sent' => !empty($data['email']),
        ], 201);
    }

    /**
     * Send notification to the added contact
     */
    private function sendContactAddedNotification(?User $contactUser, ?string $email): void
    {
        if (empty($email)) {
            return;
        }

        $currentUser = Auth::user();

        try {
            if ($contactUser) {
                // User is registered - send email and create in-app notification
                $contactUser->notify(new ContactAddedNotification($currentUser, true));
                
                // Also create a mobile notification record
                MobileNotification::create([
                    'user_id' => $contactUser->id,
                    'type' => 'contact_added',
                    'title' => 'New Contact Added You',
                    'body' => "{$currentUser->name} has added you as a contact. You can now receive calls from them.",
                    'data' => [
                        'added_by_id' => $currentUser->id,
                        'added_by_name' => $currentUser->name,
                        'added_by_email' => $currentUser->email,
                    ],
                ]);
            } else {
                // User is NOT registered - send invitation email
                $tempUser = new \stdClass();
                $tempUser->email = $email;
                $tempUser->name = 'Friend';
                
                Notification::route('mail', $email)
                    ->notify(new ContactAddedNotification($currentUser, false));
            }
        } catch (\Exception $e) {
            // Log but don't fail the contact creation
            \Log::warning('Failed to send contact added notification', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, MobileContact $contact)
    {
        if ($contact->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_favorite' => 'sometimes|boolean',
        ]);

        $contact->update($data);

        return response()->json(['success' => true, 'contact' => $contact]);
    }

    public function destroy(MobileContact $contact)
    {
        if ($contact->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $contact->delete();

        return response()->json(['success' => true]);
    }

    public function toggleFavorite(MobileContact $contact)
    {
        if ($contact->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $contact->update(['is_favorite' => !$contact->is_favorite]);

        return response()->json(['success' => true, 'is_favorite' => $contact->is_favorite]);
    }

    /**
     * Accept a contact request - creates a reverse contact entry
     */
    public function acceptContactRequest(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'notification_id' => 'nullable|integer',
        ]);

        $currentUser = Auth::user();
        $addingUser = User::find($data['user_id']);

        if (!$addingUser) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Check if contact already exists
        $existingContact = MobileContact::where('user_id', $currentUser->id)
            ->where('contact_user_id', $addingUser->id)
            ->first();

        if ($existingContact) {
            return response()->json([
                'success' => true, 
                'message' => 'Contact already exists',
                'contact' => [
                    'id' => $existingContact->id,
                    'name' => $existingContact->name,
                    'email' => $existingContact->email,
                    'is_registered' => true,
                ],
            ]);
        }

        // Create reverse contact for current user
        $contact = MobileContact::create([
            'user_id' => $currentUser->id,
            'contact_user_id' => $addingUser->id,
            'name' => $addingUser->name,
            'email' => $addingUser->email,
        ]);

        // Mark notification as read if provided
        if (!empty($data['notification_id'])) {
            MobileNotification::where('id', $data['notification_id'])
                ->where('user_id', $currentUser->id)
                ->update(['read_at' => now()]);
        }

        // Send notification to the original user that their request was accepted
        MobileNotification::create([
            'user_id' => $addingUser->id,
            'type' => 'contact_accepted',
            'title' => 'Contact Request Accepted',
            'body' => "{$currentUser->name} accepted your contact request. You can now call each other.",
            'data' => [
                'accepted_by_id' => $currentUser->id,
                'accepted_by_name' => $currentUser->name,
                'accepted_by_email' => $currentUser->email,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact request accepted',
            'contact' => [
                'id' => $contact->id,
                'name' => $contact->name,
                'phone' => $contact->phone,
                'email' => $contact->email,
                'is_favorite' => $contact->is_favorite,
                'is_registered' => true,
                'contact_user_id' => $contact->contact_user_id,
            ],
        ], 201);
    }

    /**
     * Reject a contact request
     */
    public function rejectContactRequest(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'notification_id' => 'nullable|integer',
        ]);

        $currentUser = Auth::user();

        // Mark notification as read if provided
        if (!empty($data['notification_id'])) {
            MobileNotification::where('id', $data['notification_id'])
                ->where('user_id', $currentUser->id)
                ->update(['read_at' => now()]);
        }

        // Optionally remove the original contact entry
        MobileContact::where('user_id', $data['user_id'])
            ->where('contact_user_id', $currentUser->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact request rejected',
        ]);
    }
}
