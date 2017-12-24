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
Route::group(['middleware' => ['web']], function () {
    /* =============  Frontend ==============*/
    /* == Homepage ==*/
    Route::get('/', 'HomeController@index');

    /*
     * poems
     */
    Route::group([
        'prefix' => 'poem',
    ],function (){
        Route::get('/', 'Frontend\PoemsController@index');
        Route::get('/{id}','Frontend\PoemsController@show');
    });
//Route::get('/poems/update', 'Frontend\PoemsController@updatePoemLikeCount');

    /* =============== authors =======================*/
    Route::group([
        'prefix' => 'author'
    ],function (){
        Route::get('/', 'Frontend\AuthorController@index');
        Route::get('/{id}','Frontend\AuthorController@show');
        Route::get('/{id}/poems','Frontend\AuthorController@showAllPoems');
    });

    /* =============== ajax ========================== */
    Route::group([
        'prefix' => 'ajax'
    ],function (){
        Route::post('/update/like','Frontend\PoemsController@updateLike');
        Route::post('/update/collect','Frontend\PoemsController@updateCollect');
        Route::post('/judge/like','Frontend\PoemsController@judgeLike');
        Route::get('/voiceCombine','Frontend\PoemsController@voiceCombine');
    });
    /* ============== 个人页面 ====================== */
    Route::group([
        'prefix' => 'people',
//        'middleware' => ['auth']
    ],function (){
        Route::get('/{id}','People\ShowController@index');
    });
    /* =============== 收藏=========================== */
    Route::group([
        'prefix' => '/collections',
        'middleware' => ['auth']
    ],function () {
        Route::get('/', 'People\CollectionController@index');
        Route::get('/{type}', 'People\CollectionController@index');
    });
    /* =============== 喜欢 ====================== */
    Route::group([
        'prefix' => '/likes',
        'middleware' => ['auth']
    ],function () {
        Route::get('/', 'People\LikeController@index');
        Route::get('/{type}', 'People\LikeController@index');
    });
    /* =============== 搜索功能 ====================== */
    Route::get('search','Frontend\SearchController@index');
    /* =============== 名句 ========================= */
    Route::group([
        'prefix' => 'sentence',
    ],function (){
        Route::get('/', 'Frontend\SentenceController@index');
        Route::get('/theme/{theme}','Frontend\SentenceController@getTheme');
    });
    /* =============== 专题页面 ===================== */
    Route::group([
        'prefix' =>'page'
    ],function (){
        Route::get('/{id}', 'Frontend\PageController@show');
    });
    /* =============== 静态页面 ====================== */
    Route::get('/contact','Frontend\PageController@contact');
    Route::get('/join','Frontend\PageController@join');
    Route::get('/about','Frontend\PageController@about');
});

//Route::get('/authors/update', 'Frontend\AuthorController@updateAuthorsLikeCount');

/* =============== Admin Routes ================== */

require __DIR__.'/admin_routes.php';