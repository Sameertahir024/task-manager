<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/tasks-page', [TaskController::class, 'indexPage']);
// Route::post('/tasks-store', [TaskController::class, 'store']);