<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;




Route::view('/register/form', 'page_register');
Route::view('/login', 'page_login');


Route::get('/users', function (\Illuminate\Http\Request $request) {
    return redirect('/');
});
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/', [PageController::class, 'mainPage'])->middleware('auth');
Route::get('/edit/{id}', [PageController::class, 'editPage'])->middleware('auth', 'canedit');
Route::get('/createuser', [PageController::class, 'createUserPage'])->middleware('auth','admin');
Route::get('/profile/{id}', [PageController::class, 'profilePage'])->middleware('auth');
Route::get('/status/{id}', [PageController::class, 'statusPage'])->middleware('auth','canedit');
Route::get('/usermedia/{id}', [PageController::class, 'mediaPage'])->middleware('auth','canedit');
Route::get('/security/{id}', [PageController::class, 'securityPage'])->middleware('auth','canedit');


Route::post('/register/user', [AuthController::class, 'registration']);
Route::post('/login/form', [AuthController::class, 'login']);
Route::post('/createuserform', [\App\Http\Controllers\UserController::class, 'createNewUser'])->middleware('auth','admin');
Route::post('/securityupdate/{id}', [\App\Http\Controllers\UserController::class, 'updateUserSecurity'])->middleware('auth','canedit');
Route::post('/editgetform/{id}', [\App\Http\Controllers\UserController::class, 'editUser'])->middleware('auth','canedit');
Route::post('/statusupdate/{id}', [\App\Http\Controllers\UserController::class, 'statusUser'])->middleware('auth','canedit');
Route::post('/mediaupdate/{id}', [\App\Http\Controllers\UserController::class, 'changeAvatar'])->middleware('auth','canedit');
//Route::get('/setuseradmin', [AuthController::class, 'setUserAsAdmin']);








