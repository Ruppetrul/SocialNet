<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
Route::post('/home/load_data','App\Http\Controllers\HomeController@load_home_comments')->name('load_home_comments');

Route::get('/search', 'App\Http\Controllers\SearchController@search')
    ->name('search');

Route::get('/profile/{profile_id}','App\Http\Controllers\ProfileController@profile')
    ->where(['profile_id' => '[0-9]+'])
    ->name('profile');
Route::get('/profile','App\Http\Controllers\ProfileController@profile');
Route::redirect('/profile/getComments','App\Http\Controllers\ProfileController@getComments');

Route::post('/profile/load_data', 'App\Http\Controllers\ProfileController@load_data')
    ->name('load_profile_comments');

Route::post('/profile/sendComment/{user_id}/{reply_id?}', 'App\Http\Controllers\ProfileController@sendComment')
    ->where(['user_id' => '[0-9]+',
            'reply_id' => '[0-9]+']);
Route::post('/profile/delete/{comment_id}', 'App\Http\Controllers\ProfileController@deleteComment')
    ->where(['comment_id' => '[0-9]+'])
    ->name('delete');
