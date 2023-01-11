<?php

use App\Http\Controllers\RestApi\AuthController;
use App\Http\Controllers\RestApi\UserProfileApprovedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/teacher/register', [AuthController::class, 'TeacherRegister']);
Route::post('/teacher/login', [AuthController::class, 'TeacherLogin']);
Route::middleware('auth:api')->group(function () {
Route::get('/teacher/details', [AuthController::class, 'TeacherDetails']);
Route::put('/teacher/profile/update', [AuthController::class, 'TeacherProfileUpdate']);
});
Route::put('/user/profile/approval', [UserProfileApprovedController::class, 'approveProfile']);

