<?php

use App\Http\Controllers\Account\ParticipationOutputs;
use App\Http\Controllers\Portal\PortalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your participation. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/participation_outputs', [ParticipationOutputs::class, 'calculate']);

Route::post('/new_almost_complete_action', [PortalController::class, 'new_almost_complete_action'])->name('new_almost_complete_action');
