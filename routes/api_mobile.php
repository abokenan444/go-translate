<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Mobile\DashboardController;
use App\Http\Controllers\API\Mobile\AuthController;
use App\Http\Controllers\API\Mobile\WalletController;
use App\Http\Controllers\API\Mobile\ContactsController;
use App\Http\Controllers\API\Mobile\CallHistoryController;
use App\Http\Controllers\API\Mobile\InvitesController;
use App\Http\Controllers\API\Mobile\SettingsController;
use App\Http\Controllers\API\Mobile\NotificationsController;
use App\Http\Controllers\API\Mobile\CallCostController;
use App\Http\Controllers\API\Mobile\MobileRealTimeStreamController;
use App\Http\Controllers\Api\MinutesBillingController;
use App\Http\Controllers\RealTime\RealTimeSessionController;
use App\Http\Controllers\RealTime\RealTimeParticipantController;

/*
|--------------------------------------------------------------------------
| Mobile API Routes
|--------------------------------------------------------------------------
|
| Routes specifically designed for the mobile application.
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/notifications', [DashboardController::class, 'notifications']);
    Route::post('/device-token', [DashboardController::class, 'updateDeviceToken']);

    Route::prefix('wallet')->group(function () {
        Route::get('/balance', [WalletController::class, 'balance']);
        Route::post('/topup', [WalletController::class, 'topup']);
        Route::get('/packages', [MinutesBillingController::class, 'packages']);
        Route::post('/checkout', [MinutesBillingController::class, 'createCheckout']);
        Route::get('/auto-topup', [WalletController::class, 'autoTopupSettings']);
        Route::post('/auto-topup', [WalletController::class, 'updateAutoTopup']);
        Route::get('/transactions', [WalletController::class, 'transactions']);
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactsController::class, 'index']);
        Route::post('/', [ContactsController::class, 'store']);
        Route::put('/{contact}', [ContactsController::class, 'update']);
        Route::delete('/{contact}', [ContactsController::class, 'destroy']);
        Route::post('/{contact}/favorite', [ContactsController::class, 'toggleFavorite']);
        Route::post('/accept-request', [ContactsController::class, 'acceptContactRequest']);
        Route::post('/reject-request', [ContactsController::class, 'rejectContactRequest']);
    });

    Route::get('/call-history', [CallHistoryController::class, 'index']);

    // Call cost sharing routes
    Route::prefix('calls')->group(function () {
        Route::get('/pending-cost-shares', [CallCostController::class, 'pendingRequests']);
        Route::post('/{call}/request-cost-share', [CallCostController::class, 'requestCostShare']);
        Route::post('/{call}/respond-cost-share', [CallCostController::class, 'respondCostShare']);
    });

    Route::prefix('invites')->group(function () {
        Route::get('/', [InvitesController::class, 'index']);
        Route::post('/', [InvitesController::class, 'create']);
    });

    // Call invitations for Flutter app
    Route::prefix('invitations')->group(function () {
        Route::get('/', [App\Http\Controllers\API\Mobile\InvitationsController::class, 'index']);
        Route::post('/', [App\Http\Controllers\API\Mobile\InvitationsController::class, 'store']);
        Route::post('/{id}/accept', [App\Http\Controllers\API\Mobile\InvitationsController::class, 'accept']);
        Route::post('/{id}/reject', [App\Http\Controllers\API\Mobile\InvitationsController::class, 'reject']);
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index']);
        Route::post('/', [SettingsController::class, 'update']);
        Route::get('/languages', [SettingsController::class, 'languages']);
    });

    // Notifications routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'index']);
        Route::get('/unread-count', [NotificationsController::class, 'unreadCount']);
        Route::post('/mark-all-read', [NotificationsController::class, 'markAllAsRead']);
        Route::post('/{notification}/read', [NotificationsController::class, 'markAsRead']);
        Route::delete('/{notification}', [NotificationsController::class, 'destroy']);
    });

    // Support Chat routes
    Route::prefix('support')->group(function () {
        Route::get('/availability', [App\Http\Controllers\API\Mobile\SupportChatController::class, 'checkAvailability']);
        Route::post('/chat/start', [App\Http\Controllers\API\Mobile\SupportChatController::class, 'startSession']);
        Route::get('/chat/{sessionId}', [App\Http\Controllers\API\Mobile\SupportChatController::class, 'getSession']);
        Route::get('/chat/{sessionId}/messages', [App\Http\Controllers\API\Mobile\SupportChatController::class, 'getMessages']);
        Route::post('/chat/{sessionId}/messages', [App\Http\Controllers\API\Mobile\SupportChatController::class, 'sendMessage']);
        Route::post('/chat/{sessionId}/end', [App\Http\Controllers\API\Mobile\SupportChatController::class, 'endSession']);
        Route::post('/chat/{sessionId}/rate', [App\Http\Controllers\API\Mobile\SupportChatController::class, 'rateSession']);
        Route::get('/history', [App\Http\Controllers\API\Mobile\SupportChatController::class, 'getChatHistory']);
    });

    Route::prefix('realtime')->group(function () {
        Route::post('/sessions', [RealTimeSessionController::class, 'create']);
        Route::post('/sessions/{publicId}/end', [RealTimeSessionController::class, 'end']);

        Route::post('/sessions/{publicId}/participants/join', [RealTimeParticipantController::class, 'join']);
        Route::post('/sessions/{publicId}/participants/leave', [RealTimeParticipantController::class, 'leave']);
        Route::get('/sessions/{publicId}/participants', [RealTimeParticipantController::class, 'index']);
        Route::patch('/sessions/{publicId}/participants/me', [RealTimeParticipantController::class, 'updateMe']);

        Route::post('/sessions/{publicId}/audio', [MobileRealTimeStreamController::class, 'handleAudio']);
        Route::get('/sessions/{publicId}/poll', [MobileRealTimeStreamController::class, 'pollText']);
    });
});
