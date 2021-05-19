<?php

use App\Http\Controllers\Api\ChatsController;
use App\Http\Controllers\Api\MembersController;
use App\Http\Controllers\Api\MessagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JwtAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/signup', [JwtAuthController::class, 'register']);
    Route::post('/signin', [JwtAuthController::class, 'login']);
    Route::get('/user', [JwtAuthController::class, 'user']);
    Route::post('/token-refresh', [JwtAuthController::class, 'refresh']);
    Route::post('/signout', [JwtAuthController::class, 'signout']);
});


Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'chats',
], function () {
    Route::post('/', [ChatsController::class, 'store']);
    Route::get('/', [ChatsController::class, 'index']);
    Route::get('/{chat_id}', [ChatsController::class, 'show']);

    Route::delete('/{chat_id}', [ChatsController::class, 'destroy']);
    Route::post('/{chat_id}', [ChatsController::class, 'update']);
    //MESSAGES
    Route::group([
        'prefix' => '{chat_id}/messages/',
    ], function () {
        Route::post('/', [MessagesController::class, 'store']);
        Route::get('/', [MessagesController::class, 'index']);
        Route::get('/{message_id}', [MessagesController::class, 'show']);
        Route::delete('/{message_id}', [MessagesController::class, 'destroy']);
        Route::post('/{message_id}', [MessagesController::class, 'edit']);
    });
    // MEMBERS
    Route::group([
        'prefix' => '{chat_id}/members/',
    ], function () {
        Route::post('/', [MembersController::class, 'store']);
        Route::get('/', [MembersController::class, 'index']);
        Route::get('/{member_id}', [MembersController::class, 'show']);
        Route::delete('/{member_id}', [MembersController::class, 'destroy']);
        Route::put('/{member_id}', [MembersController::class, 'update']);
    });
});


