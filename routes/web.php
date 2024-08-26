<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\MediaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	//Page
	Route::get('pages',[PageController::class, 'index'] )->name('page');
	Route::get('add-page',[PageController::class, 'addPage'] )->name('addpage');
	Route::get('edit-page/{id}',[PageController::class, 'editPage'] )->name('editpage');
	Route::post('store-page',[PageController::class, 'store'] )->name('storepage');
	Route::post('update-page',[PageController::class, 'update'] )->name('updatepage');
	Route::post('delete-page',[PageController::class, 'destroy'] )->name('pagedelete');

	//Media
	Route::get('media',[MediaController::class, 'index'] )->name('media');
	Route::get('add-media',[MediaController::class, 'addmedia'] )->name('addmedia');
	Route::get('edit-media/{id}',[MediaController::class, 'addmedia'] )->name('editmedia');
	Route::post('update-media',[MediaController::class, 'update'] )->name('updatemedia');
	Route::post('store-media',[MediaController::class, 'store'] )->name('storemedia');
	Route::post('delete-media',[MediaController::class, 'destroy'] )->name('mediadelete');
	
    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    // Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');