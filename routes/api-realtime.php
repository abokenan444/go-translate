<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RealTime\RealTimeSessionController;
use App\Http\Controllers\RealTime\RealTimeParticipantController;
use App\Http\Controllers\RealTime\RealTimeStreamController;

Route::middleware(['auth:sanctum'])
    ->prefix('realtime')
    ->group(function () {
        Route::post('sessions', [RealTimeSessionController::class, 'create'])
            ->name('realtime.sessions.create');

        Route::post('sessions/{publicId}/audio', [RealTimeStreamController::class, 'handleAudio'])
            ->name('realtime.sessions.audio');

        Route::get('sessions/{publicId}/poll', [RealTimeStreamController::class, 'pollText'])
            ->name('realtime.sessions.poll');
    });

// Participants Management
Route::post('sessions/{publicId}/participants/join', [RealTimeParticipantController::class, 'join']);
Route::post('sessions/{publicId}/participants/leave', [RealTimeParticipantController::class, 'leave']);
Route::get('sessions/{publicId}/participants', [RealTimeParticipantController::class, 'index']);
Route::patch('sessions/{publicId}/participants/{participant}', [RealTimeParticipantController::class, 'update']);
Route::post('sessions/{publicId}/participants/quality', [RealTimeParticipantController::class, 'updateQuality']);
