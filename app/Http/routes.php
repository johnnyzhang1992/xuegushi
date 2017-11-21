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
Route::get('/poems', 'Frontend\PoemsController@index');
//Route::get('/poems/update', 'Frontend\PoemsController@updatePoemLikeCount');

/* ================== Admin Routes ================== */

require __DIR__.'/admin_routes.php';