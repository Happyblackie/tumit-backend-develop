<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\TumitController;
use App\Http\Controllers\TumitaController;
use App\Http\Controllers\TumitLegalController;
use App\Http\Controllers\TumitaTumitController;
use App\Http\Controllers\TumitInviteController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\TumitRequestController;
use App\Http\Controllers\TumitaActivityController;
use App\Http\Controllers\TumitaNotificationController;

//Help Features  
Route::get('/help', [HelpController::class, 'index']);
Route::get('/help/{id}', [HelpController::class, 'show']);  // Get a specific help topic by ID

//TermsOfUse and Policy
Route::get('/terms-of-use', [TumitLegalController::class, 'termsOfUse'])->name('terms-of-use');
Route::get('/privacy-policy', [TumitLegalController::class, 'privacyPolicy'])->name('privacy-policy');

//Localization
Route::get('translations/{lang}', [LocalizationController::class, 'getTranslations']);





Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/verify-otp/{code}', [AuthController::class, 'verifyOtp'])->name('verify-otp');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::get('/tumit', [TumitController::class,'index'])->middleware('auth:api');
Route::get('/tumits/tumita/{id}', [TumitController::class,'tumitsByTumita'])->middleware('auth:api');
Route::post('/tumit', [TumitController::class,'store'])->middleware('auth:api');
Route::put('/tumit/{id}', [TumitController::class,'edit'])->middleware('auth:api');
Route::delete('/tumit/{id}', [TumitController::class,'softDelete'])->middleware('auth:api');

// Tumit invite
Route::get('/tumit/{tumitId}/invites', [TumitInviteController::class,'findTumitInvites'])->middleware('auth:api');
Route::get('/tumit/{tumitId}/invite/{inviteId}', [TumitInviteController::class,'findTumitInvite'])->middleware('auth:api');
Route::post('/tumit/invite', [TumitInviteController::class,'createTumitInvite'])->middleware('auth:api');
Route::put('/tumit/invite/{inviteId}', [TumitInviteController::class,'cancelTumitInviteBy'])->middleware('auth:api');

// Tumit request
Route::get('/tumit/{tumitId}/requests', [TumitRequestController::class,'findTumitRequests'])->middleware('auth:api');
Route::get('/tumit/{tumitId}/request/{requestId}', [TumitRequestController::class,'findTumitRequest'])->middleware('auth:api');
Route::post('/tumit/request', [TumitRequestController::class,'createTumitRequest'])->middleware('auth:api');
Route::put('/tumit/request/reject/{requestId}', [TumitRequestController::class,'rejectTumitRequest'])->middleware('auth:api');
Route::put('/tumit/request/accept/{requestId}', [TumitRequestController::class,'acceptTumitRequest'])->middleware('auth:api');
Route::put('/tumit/request/retract/{requestId}', [TumitRequestController::class,'retractTumitRequest'])->middleware('auth:api');

//***********Tumit search
Route::get('/tumit/search/{id}', [TumitController::class, 'search']);



Route::get('/tumita', [TumitaController::class,'index'])->middleware('auth:api');
Route::post('/tumita', [TumitaController::class,'store'])->middleware('auth:api');
Route::put('/tumita/{id}', [TumitaController::class,'edit'])->middleware('auth:api');
Route::patch('/tumita/username/{id}', [TumitaController::class,'changeUsername'])->middleware('auth:api');
Route::patch('/tumita/email/{id}', [TumitaController::class,'changeEmail'])->middleware('auth:api');
Route::post('/tumita/profilepic/{id}', [TumitaController::class,'changeProfilePic'])->middleware('auth:api'); // image change don't work with both PUT / Patch
Route::patch('/tumita/password/{id}', [TumitaController::class,'changePassword'])->middleware('auth:api');
Route::delete('/tumita/{id}', [TumitaController::class,'softDelete'])->middleware('auth:api');

//***********Search Tumitas
Route::get('/tumita/search/{id}', [TumitaController::class, 'search']);
// ->middleware('auth:api');


Route::get('/tumita-tumit', [TumitaTumitController::class,'index'])->middleware('auth:api');
Route::post('/tumita-tumit', [TumitaTumitController::class,'store'])->middleware('auth:api');
Route::put('/tumita-tumit/{id}', [TumitaTumitController::class,'edit'])->middleware('auth:api');
Route::delete('/tumita-tumit/{id}', [TumitaTumitController::class,'softDelete'])->middleware('auth:api');

Route::get('/tumita/notifications', [TumitaNotificationController::class,'index'])->middleware('auth:api');
Route::get('/tumita/{tumitaId}/notifications', [TumitaNotificationController::class,'findTumitaNotifications'])->middleware('auth:api');
Route::post('/tumita/notification', [TumitaNotificationController::class,'store'])->middleware('auth:api');
Route::delete('/tumita/notification/{id}', [TumitaNotificationController::class,'softDelete'])->middleware('auth:api');

Route::get('/tumita-activity', [TumitaActivityController::class,'index'])->middleware('auth:api');
Route::post('/tumita-activity', [TumitaActivityController::class,'store'])->middleware('auth:api');
Route::delete('/tumita-activity/{id}', [TumitaActivityController::class,'softDelete'])->middleware('auth:api');



