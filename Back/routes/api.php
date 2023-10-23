<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckTokenService;
use App\Http\Controllers\ControllerArea;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ControllerActions;
use App\Http\Controllers\ControllerReactions;
use App\Http\Controllers\ControllerServices;
use App\Http\Controllers\GoogleOAuthController;
use App\Http\Controllers\SpotifyAuthController;
use App\Http\Controllers\TwitchAuthController;
use App\Http\Controllers\AreaHistoriqueController;
use App\Http\Controllers\DiscordAuthController;
use App\Http\Controllers\RadioFranceAuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('custom.auth')->group(function () {
    Route::get('/test', [TestController::class, 'test']);

    // area
    Route::get('/area', [ControllerArea::class, 'index']);
    Route::get('/area/{id}', [ControllerArea::class, 'show']);
    Route::post('/area', [ControllerArea::class, 'create']);
    Route::delete('/area/{id}', [ControllerArea::class, 'delete']);
    Route::put('/area/{id}', [ControllerArea::class, 'update']);

    // actions
    Route::get('/actions', [ControllerActions::class, 'index']);
    Route::get('/actions/{areaId}', [ControllerActions::class, 'show']);
    Route::post('/actions/{areaId}', [ControllerActions::class, 'create']);
    Route::put('/actions/{areaId}', [ControllerActions::class, 'update']);
    Route::delete('/actions/{areaId}', [ControllerActions::class, 'delete']);

    // reactions
    Route::get('/reactions', [ControllerReactions::class, 'index']);
    Route::get('/reactions/{areaId}', [ControllerReactions::class, 'show']);
    Route::post('/reactions/{areaId}', [ControllerReactions::class, 'create']);
    Route::put('/reactions/{areaId}', [ControllerReactions::class, 'update']);
    Route::delete('/reactions/{areaId}', [ControllerReactions::class, 'delete']);

    // services
    Route::get('/services', [ControllerServices::class, 'index']);
    Route::get('/services/actions/{actionId}', [ControllerServices::class, 'showAction']);
    Route::get('/services/reactions/{reactionId}', [ControllerServices::class, 'showReaction']);
    Route::post('/services', [ControllerServices::class, 'create']);
    Route::put('/services/{serviceId}', [ControllerServices::class, 'update']);

    // area historique
    Route::get('/areahistorique', [AreaHistoriqueController::class, 'index']);
    Route::get('/areahistorique/{id}', [AreaHistoriqueController::class, 'show']);
    Route::post('/areahistorique', [AreaHistoriqueController::class, 'create']);
    Route::put('/areahistorique/{id}', [AreaHistoriqueController::class, 'update']);
    Route::delete('/areahistorique/{id}', [AreaHistoriqueController::class, 'delete']);

    Route::get('/checktokens', [CheckTokenService::class, 'checkTokensValidity']);
});

Route::get('/oauth2callback', [GoogleOAuthController::class, 'oauth2callback']);
Route::get('/spotify-callback', [SpotifyAuthController::class, 'spotifyCallback']);
Route::get('/twitch-callback', [TwitchAuthController::class, 'twitchCallback']);
Route::get('/discord-callback', [DiscordAuthController::class, 'discordCallback']);
Route::get('/radioFrance-callback', [RadioFranceAuthController::class, 'radioFranceCallback']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
