<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    DashboardController,
    NotificationController,
    ChatController,
    UserController
};

/*
|--------------------------------------------------------------------------
| API Routes v2
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Dashboard API
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/stats', [DashboardController::class, 'getStats']);
    Route::get('/activities/today', [DashboardController::class, 'getTodayActivities']);
});

// Notifications API
Route::prefix('notifications')->middleware('auth')->group(function () {
    Route::get('/unread', [NotificationController::class, 'getUnread']);
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
});

// Chat API
Route::prefix('chat')->middleware('auth')->group(function () {
    Route::get('/{userId}', [ChatController::class, 'getChat']);
    Route::post('/send', [ChatController::class, 'sendMessage']);
    Route::get('/unread-count', [ChatController::class, 'getUnreadCount']);
});

// Users API
Route::prefix('users')->middleware('auth')->group(function () {
    Route::get('/online', [UserController::class, 'getOnlineUsers']);
});