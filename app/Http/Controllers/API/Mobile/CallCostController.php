<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\MobileCallHistory;
use App\Models\MobileNotification;
use App\Models\MinutesWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallCostController extends Controller
{
    /**
     * Request cost sharing with the receiver
     */
    public function requestCostShare(Request $request, MobileCallHistory $call)
    {
        $user = Auth::user();
        
        // Only caller can request cost sharing
        if ($call->caller_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only the caller can request cost sharing',
            ], 403);
        }

        // Check if already requested
        if ($call->cost_share_requested) {
            return response()->json([
                'success' => false,
                'message' => 'Cost sharing already requested',
            ], 422);
        }

        // Update call
        $call->update([
            'cost_share_requested' => true,
            'cost_share_status' => 'pending',
        ]);

        // Send notification to receiver
        MobileNotification::create([
            'user_id' => $call->receiver_id,
            'type' => 'cost_share_request',
            'title' => 'مشاركة تكلفة المكالمة',
            'body' => "{$user->name} يطلب منك مشاركة تكلفة المكالمة ({$call->duration_minutes} دقيقة). هل توافق؟",
            'data' => [
                'call_id' => $call->id,
                'caller_id' => $user->id,
                'caller_name' => $user->name,
                'duration_minutes' => $call->duration_minutes,
                'share_minutes' => round($call->duration_minutes / 2, 2),
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cost sharing request sent to receiver',
        ]);
    }

    /**
     * Respond to cost sharing request (accept/reject)
     */
    public function respondCostShare(Request $request, MobileCallHistory $call)
    {
        $user = Auth::user();
        
        // Only receiver can respond
        if ($call->receiver_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only the receiver can respond to cost sharing',
            ], 403);
        }

        // Check if request is pending
        if ($call->cost_share_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'No pending cost share request',
            ], 422);
        }

        $data = $request->validate([
            'accept' => 'required|boolean',
        ]);

        $totalMinutes = $call->duration_minutes ?? 0;
        $halfMinutes = round($totalMinutes / 2, 2);

        if ($data['accept']) {
            // Check if receiver has enough balance
            $receiverWallet = MinutesWallet::where('user_id', $user->id)->first();
            $receiverBalance = $receiverWallet ? floor($receiverWallet->balance_seconds / 60) : 0;

            if ($receiverBalance < $halfMinutes) {
                return response()->json([
                    'success' => false,
                    'message' => 'رصيدك غير كافٍ لمشاركة التكلفة. تحتاج ' . $halfMinutes . ' دقيقة.',
                ], 422);
            }

            // Deduct from receiver's wallet
            if ($receiverWallet) {
                $receiverWallet->deductMinutes($halfMinutes);
            }

            // Update call
            $call->update([
                'cost_share_status' => 'accepted',
                'cost_payer' => 'shared',
                'caller_cost_minutes' => $halfMinutes,
                'receiver_cost_minutes' => $halfMinutes,
                'total_cost_minutes' => $totalMinutes,
            ]);

            // Notify caller
            MobileNotification::create([
                'user_id' => $call->caller_id,
                'type' => 'cost_share_accepted',
                'title' => 'تم قبول مشاركة التكلفة ✅',
                'body' => "{$user->name} وافق على مشاركة تكلفة المكالمة. تم خصم {$halfMinutes} دقيقة من كل طرف.",
                'data' => [
                    'call_id' => $call->id,
                    'your_share' => $halfMinutes,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cost sharing accepted',
                'your_share_minutes' => $halfMinutes,
            ]);
        } else {
            // Rejected - caller pays full cost
            $call->update([
                'cost_share_status' => 'rejected',
                'cost_payer' => 'caller',
                'caller_cost_minutes' => $totalMinutes,
                'receiver_cost_minutes' => 0,
                'total_cost_minutes' => $totalMinutes,
            ]);

            // Notify caller
            MobileNotification::create([
                'user_id' => $call->caller_id,
                'type' => 'cost_share_rejected',
                'title' => 'تم رفض مشاركة التكلفة',
                'body' => "{$user->name} رفض مشاركة تكلفة المكالمة. التكلفة الكاملة ({$totalMinutes} دقيقة) عليك.",
                'data' => [
                    'call_id' => $call->id,
                    'total_cost' => $totalMinutes,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cost sharing rejected. Full cost remains with caller.',
            ]);
        }
    }

    /**
     * Get pending cost share requests for the user
     */
    public function pendingRequests()
    {
        $user = Auth::user();

        $pending = MobileCallHistory::where('receiver_id', $user->id)
            ->where('cost_share_status', 'pending')
            ->with('caller:id,name,email')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($call) => [
                'id' => $call->id,
                'caller' => [
                    'id' => $call->caller->id ?? null,
                    'name' => $call->caller->name ?? 'Unknown',
                ],
                'duration_minutes' => $call->duration_minutes,
                'your_share_minutes' => round($call->duration_minutes / 2, 2),
                'created_at' => $call->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'pending_requests' => $pending,
        ]);
    }
}
