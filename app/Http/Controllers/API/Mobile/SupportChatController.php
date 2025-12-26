<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\SupportChatSession;
use App\Models\SupportChatMessage;
use App\Models\SupportAgentAvailability;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SupportChatController extends Controller
{
    /**
     * Check if support agents are available.
     */
    public function checkAvailability(): JsonResponse
    {
        $availableAgents = SupportAgentAvailability::available()->count();
        $waitingChats = SupportChatSession::waiting()->count();
        
        // Estimated wait time (average 5 minutes per chat)
        $estimatedWait = $availableAgents > 0 
            ? ceil($waitingChats / $availableAgents) * 5 
            : null;

        return response()->json([
            'success' => true,
            'data' => [
                'agents_available' => $availableAgents > 0,
                'available_count' => $availableAgents,
                'waiting_queue' => $waitingChats,
                'estimated_wait_minutes' => $estimatedWait,
                'support_hours' => [
                    'start' => '09:00',
                    'end' => '21:00',
                    'timezone' => 'Europe/Berlin',
                ],
            ],
        ]);
    }

    /**
     * Start a new chat session.
     */
    public function startSession(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'department' => 'nullable|string|in:general,technical,billing,translation',
            'initial_message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Check for existing active session
        $existingSession = SupportChatSession::where('user_id', $user->id)
            ->active()
            ->first();

        if ($existingSession) {
            return response()->json([
                'success' => true,
                'message' => 'Existing session found',
                'data' => [
                    'session' => $existingSession,
                    'messages' => $existingSession->messages()->orderBy('created_at')->get(),
                ],
            ]);
        }

        // Create new session
        $session = SupportChatSession::create([
            'session_id' => SupportChatSession::generateSessionId(),
            'user_id' => $user->id,
            'visitor_name' => $user->name,
            'visitor_email' => $user->email,
            'status' => 'waiting',
            'department' => $request->department ?? 'general',
            'metadata' => [
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'locale' => $request->header('Accept-Language'),
            ],
        ]);

        // Add initial message
        SupportChatMessage::create([
            'session_id' => $session->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'sender_name' => $user->name,
            'message' => $request->initial_message,
            'message_type' => 'text',
        ]);

        // Try to auto-assign available agent
        $this->tryAssignAgent($session);

        return response()->json([
            'success' => true,
            'message' => 'Chat session started',
            'data' => [
                'session' => $session->fresh(),
                'queue_position' => SupportChatSession::waiting()
                    ->where('created_at', '<', $session->created_at)
                    ->count() + 1,
            ],
        ], 201);
    }

    /**
     * Get chat session details.
     */
    public function getSession(Request $request, string $sessionId): JsonResponse
    {
        $session = SupportChatSession::where('session_id', $sessionId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'session' => $session,
                'agent' => $session->agent ? [
                    'name' => $session->agent->name,
                    'avatar' => $session->agent->avatar_url ?? null,
                ] : null,
            ],
        ]);
    }

    /**
     * Get messages for a session.
     */
    public function getMessages(Request $request, string $sessionId): JsonResponse
    {
        $session = SupportChatSession::where('session_id', $sessionId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found',
            ], 404);
        }

        $lastMessageId = $request->query('after', 0);
        
        $messages = $session->messages()
            ->when($lastMessageId > 0, function ($q) use ($lastMessageId) {
                $q->where('id', '>', $lastMessageId);
            })
            ->orderBy('created_at')
            ->get();

        // Mark agent messages as read
        $session->messages()
            ->where('sender_type', 'agent')
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'success' => true,
            'data' => [
                'messages' => $messages,
                'session_status' => $session->status,
            ],
        ]);
    }

    /**
     * Send a message in a session.
     */
    public function sendMessage(Request $request, string $sessionId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
            'type' => 'nullable|string|in:text,image,file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        
        $session = SupportChatSession::where('session_id', $sessionId)
            ->where('user_id', $user->id)
            ->whereIn('status', ['waiting', 'active'])
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found or closed',
            ], 404);
        }

        $message = SupportChatMessage::create([
            'session_id' => $session->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'sender_name' => $user->name,
            'message' => $request->message,
            'message_type' => $request->type ?? 'text',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => $message,
            ],
        ], 201);
    }

    /**
     * End a chat session.
     */
    public function endSession(Request $request, string $sessionId): JsonResponse
    {
        $session = SupportChatSession::where('session_id', $sessionId)
            ->where('user_id', $request->user()->id)
            ->whereIn('status', ['waiting', 'active'])
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found or already closed',
            ], 404);
        }

        // Release agent if assigned
        if ($session->agent_id) {
            $agentAvailability = SupportAgentAvailability::where('user_id', $session->agent_id)->first();
            if ($agentAvailability) {
                $agentAvailability->decrementChats();
            }
        }

        $session->close();

        return response()->json([
            'success' => true,
            'message' => 'Chat session ended',
        ]);
    }

    /**
     * Rate a completed chat session.
     */
    public function rateSession(Request $request, string $sessionId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $session = SupportChatSession::where('session_id', $sessionId)
            ->where('user_id', $request->user()->id)
            ->where('status', 'closed')
            ->whereNull('rating')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found or already rated',
            ], 404);
        }

        $session->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback',
        ]);
    }

    /**
     * Get user's chat history.
     */
    public function getChatHistory(Request $request): JsonResponse
    {
        $sessions = SupportChatSession::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($session) {
                return [
                    'session_id' => $session->session_id,
                    'status' => $session->status,
                    'department' => $session->department,
                    'started_at' => $session->created_at,
                    'ended_at' => $session->ended_at,
                    'rating' => $session->rating,
                    'last_message' => $session->messages()->latest()->first()?->message,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'sessions' => $sessions,
            ],
        ]);
    }

    /**
     * Try to assign an available agent to the session.
     */
    private function tryAssignAgent(SupportChatSession $session): void
    {
        $agent = SupportAgentAvailability::available()
            ->when($session->department, function ($q, $dept) {
                $q->whereJsonContains('departments', $dept);
            })
            ->orderBy('current_chats')
            ->first();

        if ($agent) {
            $session->assignAgent($agent->user);
            $agent->incrementChats();

            // Send system message
            SupportChatMessage::create([
                'session_id' => $session->id,
                'sender_type' => 'system',
                'sender_name' => 'System',
                'message' => 'An agent has joined the chat.',
                'message_type' => 'system',
            ]);
        }
    }
}
