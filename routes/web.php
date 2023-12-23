<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'main', 'as' => 'main.'], function () {
        Route::get('/index', '\App\Http\Controllers\MainController@index')->name('index');
        Route::get('/form', '\App\Http\Controllers\MainController@form')->name('form');
        Route::get('/delete-all', '\App\Http\Controllers\MainController@deleteAll')->name('delete-all');
        Route::get('/delete/{id}', '\App\Http\Controllers\MainController@delete')->name('delete');
        Route::get('/view/{slug}', '\App\Http\Controllers\MainController@view')->name('view');
        Route::post('/submit', '\App\Http\Controllers\MainController@submit')->name('submit');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', '\App\Http\Controllers\SettingController@index')->name('index');
        Route::post('/submit', '\App\Http\Controllers\SettingController@submit')->name('submit');

    });

    Route::group(['prefix' => 'components', 'as' => 'components.'], function () {
        Route::get('/alert', function () {
            return view('admin.component.alert');
        })->name('alert');
        Route::get('/accordion', function () {
            return view('admin.component.accordion');
        })->name('accordion');
    });
});
