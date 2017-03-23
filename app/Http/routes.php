<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'web'], function () {
    //homepage
    Route::get('/', ['as' => 'web.home', 'uses' => 'PagesController@home']);
    Route::get('blog', ['as' => 'web.blog', 'uses' => 'PagesController@home']);
    Route::get('/blog/{posts}', ['as' => 'web.post', 'uses' => 'PagesController@post']);
    Route::get('/category/{categories}', ['as' => 'web.category', 'uses' => 'PagesController@category']);

});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => 'Api'], function () {

    // Posts
    Route::post('posts/{posts}/publish', ['as' => 'api.posts.publish', 'uses' => 'PostsController@publish']);
    Route::post('posts/{posts}/image', ['as' => 'api.posts.updateImage', 'uses' => 'PostsController@updateImage']);
    Route::get('posts/medias/{posts}', [ 'as' => 'api.posts.medias.index', 'uses' => 'PostsController@medias']);
    Route::patch('posts/putInFront/{medias}', ['as' => 'api.putInFront.medias', 'uses' => 'PostsController@putInFront']);
    Route::resource('posts', 'PostsController', ['except' => ['create', 'edit']]);

    // Categories
    Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);

    // Medias
    Route::get('medias/unique/{medias}', ['as' => 'api.unique.medias', 'uses' => 'MediasController@unique']);
    Route::resource('medias', 'MediasController', ['except' => ['create', 'edit']]);

    // Users
    Route::resource('users', 'UsersController', ['except' => ['create', 'edit']]);

    // Posts categories
    Route::patch('posts/{posts}/categories', ['as' => 'api.posts.categories.sync', 'uses' => 'PostsCategoriesController@sync']);
    Route::resource('posts.categories', 'PostsCategoriesController', ['only' => ['index', 'store', 'destroy']]);

    // Categories posts
    Route::get('categories/{categories}/posts', [ 'as' => 'api.categories.posts.index', 'uses' => 'CategoriesPostsController@index']);

    // Users
    Route::get('me', ['as' => 'api.me.show', 'uses' => 'MeController@show']);
    Route::patch('me', ['as' => 'api.me.update', 'uses' => 'MeController@update']);
    Route::put('me', ['as' => 'api.me.update', 'uses' => 'MeController@update']);

});

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/{vue_capture?}', function () {
        return view('admin.index');
    })->where('vue_capture', '[\/\w\.-]*');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::auth();
