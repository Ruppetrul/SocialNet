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

Route::get('/search', 'App\Http\Controllers\SearchController@search')
    ->name('search');

Route::get('/profile/{profile_id}','App\Http\Controllers\ProfileController@profile')
    ->name('profile');
Route::redirect('/profile','/home');
Route::redirect('/profile/getComments','App\Http\Controllers\ProfileController@getComments');

// POST-запрос при нажатии на нашу кнопку.
/*
Route::post('/profile/load_data', 'App\Http\Controllers\ProfileController@load_data')
    ->name('load_data');*/

Route::post('/profile/load_data', function () {
   dd($_SESSION);
})->name('load_data');
// Фильтр, срабатывающий перед пост запросом.
/*Route:: ('csrf-ajax', function()
{
    if (Session::token() != Request::header('x-csrf-token'))
    {
        throw new Illuminate\Session\TokenMismatchException;
    }
});*/

Route::post('/profile/sendComment/{user_id}/{reply_id?}', 'App\Http\Controllers\ProfileController@sendComment');
Route::post('/profile/delete/{comment_id}', 'App\Http\Controllers\ProfileController@deleteComment')
    ->name('delete');
