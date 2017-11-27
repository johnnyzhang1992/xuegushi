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

/* =============  Frontend ==============*/
/* == Homepage ==*/
Route::get('/', 'HomeController@index');

/*
 * poems
 */
Route::group([
    'prefix' => 'poem'
],function (){
    Route::get('/', 'Frontend\PoemsController@index');
    Route::get('/{id}','Frontend\PoemsController@show');
});
//Route::get('/poems/update', 'Frontend\PoemsController@updatePoemLikeCount');

/*
 * authors
 */
Route::group([
    'prefix' => 'author'
],function (){
    Route::get('/', 'Frontend\AuthorController@index');
    Route::get('/{id}','Frontend\AuthorController@show');
});

/* =============== ajax ========================== */
Route::group([
    'prefix' => 'ajax'
],function (){
    Route::post('/update/like_count','Frontend\PoemsController@updateLikeCount');
    Route::post('/judge/like','Frontend\PoemsController@judgeLike');
});
/* =============== 静态页面 ====================== */

Route::get('/contact','Frontend\PageController@contact');
Route::get('/join','Frontend\PageController@join');
Route::get('/about','Frontend\PageController@about');
//Route::get('/authors/update', 'Frontend\AuthorController@updateAuthorsLikeCount');

/* =============== Admin Routes ================== */

require __DIR__.'/admin_routes.php';