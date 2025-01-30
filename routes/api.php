<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
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

// Authentication routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    // Home routes
    Route::post('/createGroup', [BudgetController::class, 'createGroup']);
    Route::get('/getGroups', [BudgetController::class, 'getGroups']);

    Route::prefix('budgetPlan')->group(function () {
        Route::post('/createBudgetPlan', [BudgetController::class, 'createBudgetPlan']);
        Route::post('/addItem', [BudgetController::class, 'addItem']);    
        Route::get('/getItems', [BudgetController::class, 'getItems']);
        Route::get('/getBudgetPlan', [BudgetController::class, 'getBudgetPlan']);
        Route::delete('/deleteItem', [BudgetController::class, 'deleteItem']);
        Route::delete('/deleteBudgetPlan', [BudgetController::class, 'deletePlan']);
    });
});