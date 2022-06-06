<?php

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
Route::group(['middleware' => 'admin'], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/posts/categories', 'PostsController@posts_categories_index');
        Route::get('/posts/categories/create', 'PostsController@posts_categories_create');
        Route::post('/posts/categories/store', 'PostsController@posts_categories_store');
        Route::get('/posts/categories/edit/{id}', 'PostsController@posts_categories_edit');
        Route::post('/posts/categories/update/{id}', 'PostsController@posts_categories_Update');
        Route::get('/posts/comments', 'PostsController@posts_comments_index');
        Route::resource('/posts', 'PostsController');
    });
});
