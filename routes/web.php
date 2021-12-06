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
Route::get('/home','App\Http\Controllers\HomeController@home')
    ->name('home')
    ->middleware('auth');

Route::post('/home/load_data','App\Http\Controllers\HomeController@load_home_comments')
    ->name('load_home_comments');

Route::get('/search', 'App\Http\Controllers\SearchController@search')
    ->name('search');

Route::get('/profile/{profile_id}','App\Http\Controllers\ProfileController@profile')
    ->where(['profile_id' => '[0-9]+'])
    ->name('profile');

Route::get('/profile','App\Http\Controllers\ProfileController@profile')
    ->middleware('auth');

Route::post('/profile/load_data', 'App\Http\Controllers\ProfileController@load_data')
    ->name('load_profile_comments');

Route::post('/profile/sendComment/{user_id}/{reply_id?}', 'App\Http\Controllers\ProfileController@sendComment')
    ->where(['user_id' => '[0-9]+',
            'reply_id' => '[0-9]+'])
    ->middleware('auth');

Route::post('/profile/delete/{comment_id}', 'App\Http\Controllers\ProfileController@deleteComment')
    ->where(['comment_id' => '[0-9]+'])
    ->middleware('auth')
    ->name('delete');

Route::get('/library/{id_user?}', 'App\Http\Controllers\LibraryController@library')
    ->where(['id_user' => '[0-9]+'])
    ->middleware('auth')
    ->name('library');

Route::post('/library/load_data', 'App\Http\Controllers\LibraryController@load_data')
    ->middleware('auth')
    ->name('load_books');

Route::post('/library/allow_access', 'App\Http\Controllers\LibraryController@allow_access')
    ->middleware('auth')
    ->name('allow_access');

Route::post('/library/limit_access', 'App\Http\Controllers\LibraryController@limit_access')
    ->middleware('auth')
    ->name('limit_access');

Route::post('/library/add_book', 'App\Http\Controllers\LibraryController@add_book')
    ->middleware('auth')
    ->name('add_book');

Route::post('/library/delete_book', 'App\Http\Controllers\LibraryController@delete_book')
    ->middleware('auth')
    ->name('delete_book');

Route::get('/library/edit_book/{book_id?}', 'App\Http\Controllers\LibraryController@edit_book')
    ->where(['book_id' => '[0-9]+'])
    ->middleware('auth')
    ->name('edit_book');

Route::post('/library/alter_book/{book_id}', 'App\Http\Controllers\LibraryController@alter_book')
    ->where(['book_id' => '[0-9]+'])
    ->middleware('auth')
    ->name('alter_book');

Route::get('/library/read_book/{book_id}', 'App\Http\Controllers\LibraryController@read_book')
    ->where(['book_id' => '[0-9]+'])
    ->middleware('auth')
    ->name('read_book');

Route::post('/library/share_book', 'App\Http\Controllers\LibraryController@share_book')
    ->where(['book_id' => '[0-9]+'])
    ->middleware('auth')
    ->name('share_book');

Route::get('/library/read_share_book/{id_book}',
    'App\Http\Controllers\LibraryController@read_share_book')
    ->where(['book_id' => '[0-9]+'])
    ->name('read_share_book')
    ->middleware(['auth','shared']);

