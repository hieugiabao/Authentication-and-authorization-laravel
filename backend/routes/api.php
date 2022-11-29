<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
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

Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth.jwt');
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth.jwt');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh'])->withoutMiddleware('auth.jwt');
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group([
    'middleware' => ['auth.jwt', 'role:superadmin|admin'],
    'prefix' => 'roles',
], function ($router) {
    Route::get('/', [RoleController::class, 'getAllRoles']);
    Route::post('/', [RoleController::class, 'create'])->middleware('permission:manage-role');
    Route::get('/{id}', [RoleController::class, 'getRole']);
    Route::put('/{id}', [RoleController::class, 'update'])->middleware('permission:manage-role');
});

// register permission
Route::group([
    'middleware' => ['auth.jwt', 'role:superadmin|admin'],
    'prefix' => 'permissions',
], function ($router) {
    Route::get('/', [PermissionController::class, 'getAllPermissions']);
    Route::post('/', [PermissionController::class, 'create'])->middleware('permission:manage-permission');
    Route::get('/{id}', [PermissionController::class, 'getPermission']);
    Route::put('/{id}', [PermissionController::class, 'update'])->middleware('permission:manage-permission');
});
