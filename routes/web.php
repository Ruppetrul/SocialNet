<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/home');
});
Route::get('/home','App\Http\Controllers\HomeController@home')->name('home');

Route::get('/search', 'App\Http\Controllers\SearchController@search')
    ->name('search');

Route::get('/profile/{profile_id}','App\Http\Controllers\ProfileController@profile')
    ->name('profile');
Route::redirect('/profile','/home');

Route::post('/profile/sendComment/{user_id}/{reply_id?}', 'App\Http\Controllers\ProfileController@sendComment');
Route::post('/profile/delete/{comment_id}', 'App\Http\Controllers\ProfileController@deleteComment')
    ->name('delete');
