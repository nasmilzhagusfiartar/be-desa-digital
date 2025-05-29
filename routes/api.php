<?php

use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\HeadOfFamilyController;
use App\Http\Controllers\SocialAssistanceController;
use App\Http\Controllers\SocialAssistanceRecipientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::apiResource('user', UserController::class);
Route::get('user/all/paginated', [UserController::class, 'getAllPaginated']);

Route::apiResource('head-of-family', HeadOfFamilyController::class);
Route::get('head-of-family/all/paginated', [HeadOfFamilyController::class, 'getAllPaginated']);

Route::apiResource('family-member', FamilyMemberController::class);
Route::get('family-member/all/paginated', [FamilyMemberController::class, 'getAllPaginated']);

Route::apiResource('social-assistance', SocialAssistanceController::class);
Route::get('social-assistance/all/paginated', [SocialAssistanceController::class, 'getAllPaginated']);
    
Route::apiResource('social-assistance-recipients', SocialAssistanceRecipientController::class);
Route::get('social-assistance-recipients/all/paginated', [SocialAssistanceRecipientController::class, 'getAllPaginated']);


Route::apiResource('event', EventController::class);
Route::get('event/all/paginated', [EventController::class, 'getAllPaginated']);

Route::apiResource('event-participant', EventParticipantController::class);
Route::get('event-participant/all/paginated', [EventParticipantController::class, 'getAllPaginated']);

Route::apiResource('development', DevelopmentController::class);
Route::get('development/all/paginated', [DevelopmentController::class, 'getAllPaginated']);
