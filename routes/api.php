<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'API\Auth\RegisterController')->name('register');
Route::post('login', 'API\Auth\LoginController@login')->name('login');
Route::post('password/email', 'API\Auth\ForgotPasswordController')->name('password.request');
Route::post('password/reset', 'API\Auth\ResetPasswordController')->name('password.update');

Route::get('explore', 'API\PublicPostController')->name('posts.explore');
Route::get('posts/{post}', 'API\Post\PostController@show')->name('posts.show');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', 'API\Auth\LoginController@logout')->name('logout');
    Route::get('me', 'API\Account\MeController')->name('me');

    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
        Route::get('/', 'API\Post\PostController@index')->name('index');
        Route::post('/', 'API\Post\PostController@store')->name('store');
        Route::put('{post}', 'API\Post\PostController@update')->name('update');
        Route::put('{post}/publish', 'API\Post\ActionController@publish')->name('publish');
        Route::put('{post}/un-publish', 'API\Post\ActionController@unPublish')->name('unPublish');
        Route::delete('{post}', 'API\Post\PostController@destroy')->name('delete');
    });

    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', 'API\CategoryController@index')->name('index');
        Route::post('/', 'API\CategoryController@store')->name('store');
        Route::get('{category}', 'API\CategoryController@show')->name('show');
        Route::put('{category}', 'API\CategoryController@update')->name('update');
        Route::delete('{category}', 'API\CategoryController@destroy')->name('destroy');
    });
});
