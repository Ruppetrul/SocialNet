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


Route::prefix('/profile')->middleware(['auth'])->group(function () {
    Route::get('/','App\Http\Controllers\ProfileController@profile')
        ->middleware('library.access');


    Route::get('/{user}',[ProfileController::class,'profile'])
        ->where(['id' => '[0-9]+'])
        ->middleware('library.access')
        ->name('profile');

    Route::post('/load_data', 'App\Http\Controllers\ProfileController@load_data')
        ->withoutMiddleware('auth')
        ->name('load_profile_comments');

    Route::post('/sendComment/{id_user}/{id_reply?}', 'App\Http\Controllers\ProfileController@sendComment')
        ->where(['id_user' => '[0-9]+',
            'id_reply' => '[0-9]+']);

    Route::post('/delete/{id_comment}', 'App\Http\Controllers\ProfileController@deleteComment')
        ->where(['id_comment' => '[0-9]+'])
        ->name('delete');
});

Route::prefix('/library')->middleware(['auth'])->group(function() {

    Route::get('/{id_user?}', 'App\Http\Controllers\LibraryController@library')
        ->where(['id_user' => '[0-9]+'])
        ->name('library');

    Route::post('/load_data', 'App\Http\Controllers\LibraryController@load_data')
        ->name('load_books');

    Route::post('/allow_access', 'App\Http\Controllers\LibraryController@allow_access')
        ->name('allow_access');

    Route::post('/limit_access', 'App\Http\Controllers\LibraryController@limit_access')
        ->name('limit_access');

    Route::post('/add_book', 'App\Http\Controllers\LibraryController@add_book')
        ->name('add_book');

    Route::post('/delete_book', 'App\Http\Controllers\LibraryController@delete_book')
        ->name('delete_book');

    Route::get('/edit_book/{id_book?}', 'App\Http\Controllers\LibraryController@edit_book')
        ->where(['id_book' => '[0-9]+'])
        ->name('edit_book');

    Route::post('/alter_book/{id_book}', 'App\Http\Controllers\LibraryController@alter_book')
        ->where(['id_book' => '[0-9]+'])
        ->name('alter_book');

    Route::get('/read_book/{id_book}', 'App\Http\Controllers\LibraryController@read_book')
        ->where(['id_book' => '[0-9]+'])
        ->name('read_book');

    Route::post('/share_book', 'App\Http\Controllers\LibraryController@share_book')
        //->where(['id_book' => '[0-9]+'])
        ->name('share_book');

    Route::get('/read_share_book/{id_book}',
        'App\Http\Controllers\LibraryController@read_share_book')
        ->where(['id_book' => '[0-9]+'])
        ->name('read_share_book')
        ->middleware('shared');
});



